<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class Condition
 * @package App\Core\V201\Repositories\Activity
 */
class Condition
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
        $activity->conditions = $activityDetails;

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getConditionData($activityId)
    {
        return $this->activity->findOrFail($activityId)->conditions;
    }
}
