<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

class Budget
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
     * update activity date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->budget = $activityDetails['budget'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getBudgetData($activityId)
    {
        return $this->activity->findorFail($activityId)->budget;
    }
}
