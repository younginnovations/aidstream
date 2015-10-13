<?php namespace App\Core\V201\Wizard\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class StepThree
 * @package app\Core\V201\Wizard\Repositories\Activity
 */
class StepThree
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
     * update activity status and activity date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->activity_status = $activityDetails['activity_status'];
        $activity->activity_date   = $activityDetails['activity_date'];

        return $activity->save();
    }
}
