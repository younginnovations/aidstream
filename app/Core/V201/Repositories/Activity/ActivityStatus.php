<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityStatus
 * @package App\Core\V201\Repositories\Activity
 */
class ActivityStatus
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
     * update Activity Status
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->activity_status = $activityDetails['activity_status'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityStatusData($activityId)
    {
        return $this->activity->findorFail($activityId)->activity_status;
    }
}
