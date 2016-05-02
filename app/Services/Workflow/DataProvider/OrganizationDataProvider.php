<?php namespace App\Services\Workflow\DataProvider;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\OrganizationPublished;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrganizationDataProvider
 * @package App\Services\Workflow\DataProvider
 */
class OrganizationDataProvider
{
    /**
     * @var Organization
     */
    protected $organization;
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var ActivityPublished
     */
    protected $activityPublished;
    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;

    /**
     * @var
     */
    protected $organizationId;

    /**
     * Organization Model
     * @var null
     */
    protected $organizationModel = null;

    /**
     * Segmented Publishing Type.
     */
    const SEGMENTED_PUBLISHING_TYPE = 'segmented';

    /**
     * Unsegmented Publishing Type.
     */
    const UNSEGMENTED_PUBLISHING_TYPE = 'unsegmented';

    /**
     * OrganizationDataProvider constructor.
     * @param Organization          $organization
     * @param Activity              $activity
     * @param ActivityPublished     $activityPublished
     * @param OrganizationPublished $organizationPublished
     */
    public function __construct(Organization $organization, Activity $activity, ActivityPublished $activityPublished, OrganizationPublished $organizationPublished)
    {
        $this->organization          = $organization;
        $this->activity              = $activity;
        $this->activityPublished     = $activityPublished;
        $this->organizationPublished = $organizationPublished;
    }

    /**
     * Get an Organization Model with a specific organizationId.
     * @param $organizationId
     * @return Organization
     */
    public function find($organizationId)
    {
        return $this->organization->findOrFail($organizationId);
    }

    /**
     * Get the current status of the xml files according to the organization's segmentation/publishing type.
     * @param $currentFiles
     * @return array
     */
    public function getCurrentStatus(Collection $currentFiles)
    {
        $currentStatus = [];

        if (!$currentFiles->isEmpty()) {
            $currentFiles->each(
                function ($file) use (&$currentStatus) {
                    $currentStatus[$file->filename]['included_activities'] = $file->extractActivityId();
                    $currentStatus[$file->filename]['published_status']    = $file->published_to_register;
                }
            );
        }

        return $currentStatus;
    }

    /**
     * Delete old xml file record from the database.
     * @param $filename
     * @param $organizationId
     */
    public function deleteOldData($filename, $organizationId)
    {
        $this->activityPublished->query()->where('filename', '=', $filename)->where('organization_id', '=', $organizationId)->delete();
    }

    /**
     * Update published status for the changed files.
     * @param $changes
     * @param $organizationId
     */
    public function updateStatus($changes, $organizationId)
    {
        $this->organizationModel = $this->organization->findOrFail($organizationId);

        foreach ($changes['changes'] as $filename => $change) {
            $row                        = $this->activityPublished->query()->where('filename', '=', $filename)->where('organization_id', '=', $organizationId)->first();
            $row->published_to_register = 1;

            $row->save();
        }
    }

    /**
     * Find an Activity with a specific id.
     * @param $id
     * @return mixed
     */
    public function findActivity($id)
    {
        return $this->activity->findOrFail($id);
    }

    /**
     * Get the file being published for the Activity with a specific activityId.
     * @param $activityId
     * @return ActivityPublished|null
     */
    public function fileBeingPublished($activityId)
    {
        $activity    = $this->activity->findOrFail($activityId);
        $settings    = $activity->organization->settings;
        $publisherId = getVal($settings->registry_info, [0, 'publisher_id']);

        return $this->getFile($publisherId, $settings->publishing_type, $activityId, $activity->organization_id);
    }

    /**
     * Get the ActivityPublished record for the current file being published.
     * @param $publisherId
     * @param $publishingType
     * @param $activityId
     * @param $organizationId
     * @return ActivityPublished|null
     */
    protected function getFile($publisherId, $publishingType, $activityId, $organizationId)
    {
        if ($publishingType == self::UNSEGMENTED_PUBLISHING_TYPE) {
            $filename = sprintf('%s-%s.xml', $publisherId, 'activities');

            return $this->activityPublished->query()
                                           ->where('organization_id', '=', $organizationId)
                                           ->where('filename', '=', $filename)
                                           ->latest()
                                           ->first();
        }

        return $this->getSegmentedFilename($publisherId, $activityId, $organizationId);
    }

    /**
     * Get the segmented filename
     * @param $publisherId
     * @param $activityId
     * @param $organizationId
     * @return ActivityPublished|null
     */
    protected function getSegmentedFilename($publisherId, $activityId, $organizationId)
    {
        $activityFile = sprintf('%s-%s.xml', $publisherId, $activityId);
        $activities   = $this->activityPublished->query()
                                                ->where('organization_id', '=', $organizationId)
                                                ->latest()
                                                ->get();

        $requiredActivity = null;

        $activities->each(
            function ($activity) use ($activityFile, &$requiredActivity) {
                if ($publishedActivities = $activity->published_activities) {
                    if (in_array($activityFile, $publishedActivities)) {
                        $requiredActivity = $activity;
                    }
                }
            }
        );

        return $requiredActivity;
    }

    public function unsetPublishedFlag($changes)
    {
        foreach ($changes['previous'] as $filename => $previous) {
            $filePath = public_path('files/xml/') . $filename;
            if ($this->activityPublished->where('filename', '=', $filename)->delete()) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}
