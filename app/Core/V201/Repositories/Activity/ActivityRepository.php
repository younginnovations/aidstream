<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInRegistry;
use App\Models\ActivityPublished;

/**
 * Class ActivityRepository
 * @package app\Core\V201\Repositories\Activity
 */
class ActivityRepository
{
    protected $activity;

    /**
     * @param Activity           $activity
     * @param ActivityPublished  $activityPublished
     * @param ActivityInRegistry $activityInRegistry
     */
    public function __construct(Activity $activity, ActivityPublished $activityPublished, ActivityInRegistry $activityInRegistry)
    {
        $this->activity           = $activity;
        $this->activityPublished  = $activityPublished;
        $this->activityInRegistry = $activityInRegistry;
    }

    /**
     * insert activity data to database
     * @param array $input
     * @param       $organizationId
     * @param array $defaultFieldValues
     * @return modal
     */
    public function store(array $input, $organizationId, array $defaultFieldValues)
    {
        unset($input['_token']);

        return $this->activity->create(
            [
                'identifier'           => $input,
                'organization_id'      => $organizationId,
                'default_field_values' => $defaultFieldValues
            ]
        );
    }

    /**
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId)
    {
        return $this->activity->where('organization_id', $organizationId)->orderBy('updated_at','desc')->get();
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

    /**
     * @param       $activityId
     * @param array $defaultFieldValues
     * @return mixed
     */
    public function saveDefaultValues($activityId, array $defaultFieldValues)
    {
        $activity                       = $this->activity->find($activityId);
        $activity->collaboration_type   = $defaultFieldValues[0]['default_collaboration_type'];
        $activity->default_flow_type    = $defaultFieldValues[0]['default_flow_type'];
        $activity->default_finance_type = $defaultFieldValues[0]['default_finance_type'];
        $activity->default_aid_type     = $defaultFieldValues[0]['default_aid_type'];
        $activity->default_tied_status  = $defaultFieldValues[0]['default_tied_status'];

        return $activity->save();
    }

    /**
     * @param Activity $activityData
     * @return bool
     */
    public function makePublished(Activity $activityData)
    {
        $activityData->published_to_registry = 1;

        return $activityData->save();
    }

    /**
     * @param $activityData
     * @param $jsonData
     * @return bool
     */
    public function saveActivityRegistryData($activityData, $jsonData)
    {
        $activityInRegistry                  = $this->activityInRegistry->firstOrNew(['activity_id' => $activityData->id]);
        $activityInRegistry->organization_id = session('org_id');
        $activityInRegistry->activity_id     = $activityData->id;
        $activityInRegistry->activity_data   = $jsonData;
        if ($activityInRegistry->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $organizationId
     * @return mixed
     */
    public function getDataForOrganization($organizationId)
    {
        return $this->activityInRegistry->whereOrganizationId($organizationId)->get();
    }

    /**
     * @param $filename
     * @param $orgId
     * @return mixed
     */
    public function getActivityPublished($filename, $orgId)
    {
        return $this->activityPublished->where('filename', '=', $filename)
                                       ->whereOrganizationId($orgId)
                                       ->first();
    }

    /**
     * @param $activityId
     * @param $jsonData
     * @return bool
     */
    public function saveBulkPublishDataInActivityRegistry($activityId, $jsonData)
    {
        $activityInRegistry                  = $this->activityInRegistry->firstOrNew(['activity_id' => $activityId]);
        $activityInRegistry->organization_id = session('org_id');
        $activityInRegistry->activity_id     = $activityId;
        $activityInRegistry->activity_data   = $jsonData;

        return $activityInRegistry->save();
    }
}
