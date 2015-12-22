<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;

/**
 * Class ActivityRepository
 * @package app\Core\V201\Repositories\Activity
 */
class ActivityRepository
{
    protected $activity;

    /**
     * @param Activity          $activity
     * @param ActivityPublished $activityPublished
     */
    public function __construct(Activity $activity, ActivityPublished $activityPublished)
    {
        $this->activity          = $activity;
        $this->activityPublished = $activityPublished;
    }

    /**
     * insert activity data to database
     * @param array $input
     * @param       $organizationId
     * @return modal
     */
    public function store(array $input, $organizationId)
    {
        unset($input['_token']);

        return $this->activity->create(
            [
                'identifier'      => $input,
                'organization_id' => $organizationId
            ]
        );
    }

    /**
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId)
    {
        return $this->activity->where('organization_id', $organizationId)->get();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return $this->activity->findorFail($activityId);
    }

    /**
     * @param array    $input
     * @param Activity $activityData
     * @return bool
     */
    public function updateStatus(array $input, Activity $activityData)
    {
        $activityData->activity_workflow = $input['activity_workflow'];

        return $activityData->save();
    }

    /**
     * @param $activity_id
     */
    public function resetActivityWorkflow($activity_id)
    {
        $this->activity->whereId($activity_id)->update(['activity_workflow' => 0]);
    }

    /**
     * @param $org_id
     * @return mixed
     */
    public function getActivityPublishedFiles($org_id)
    {
        return $this->activityPublished->whereOrganizationId($org_id)->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        $result = $this->activityPublished->find($id);
        if ($result) {
            $file   = public_path('uploads/files/activity/' . $result->filename);
            $result = $result->delete();
            if ($result && file_exists($file)) {
                unlink($file);
            }
        }

        return $result;
    }

    /**
     * @param $publishedId
     * @return mixed
     */
    public function updatePublishToRegister($publishedId)
    {
        $activityPublished                        = $this->activityPublished->find($publishedId);
        $activityPublished->published_to_register = 1;

        return $activityPublished->save();
    }
}
