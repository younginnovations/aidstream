<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;


/**
 * Class ActivityDate
 * @package App\Core\V201\Repositories\Activity
 */
class ActivityDate
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
        $activity->activity_date = $activityDetails['activity_date'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityDateData($activityId)
    {
        return $this->activity->findorFail($activityId)->activity_date;
    }

}
