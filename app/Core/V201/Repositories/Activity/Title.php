<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class Title
 * Contains the functions that return activity data and update activity title.
 * @package App\Core\V201\Repositories\Activity
 */
class Title
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
     * update activity title
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->title = $activityDetails['title'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return Activity::findorFail($activityId);
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getTitleData($activityId)
    {
        return Activity::findorFail($activityId)->title;
    }
}
