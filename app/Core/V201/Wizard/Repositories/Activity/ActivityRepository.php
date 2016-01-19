<?php namespace App\Core\V201\Wizard\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityRepository
 * @package app\Core\V201\Repositories\Activity
 */
class ActivityRepository
{
    protected $activity;

    /**
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * save new activity from wizard
     * @param array $identifier
     * @param array $defaultFieldValues
     * @param       $organizationId
     * @return static
     */
    public function store(array $identifier, array $defaultFieldValues, $organizationId)
    {
        return $this->activity->create(
            [
                'identifier'           => $identifier,
                'default_field_values' => $defaultFieldValues,
                'organization_id'      => $organizationId
            ]
        );
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return $this->activity->findOrFail($activityId);
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
}
