<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class DefaultFlowType
 * @package App\Core\V201\Repositories\Activity
 */
class DefaultFlowType
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @param Activity $activity
     */
    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update Default Flow Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->default_flow_type = $activityDetails['default_flow_type'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getDefaultFlowTypeData($activityId)
    {
        return $this->activity->findOrFail($activityId)->default_flow_type;
    }
}
