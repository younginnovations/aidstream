<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityScope
 * @package App\Core\V201\Repositories\Activity
 */
class ActivityScope
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
     * update Activity Scope
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->activity_scope = $activityDetails['activity_scope'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityScopeData($activityId)
    {
        return $this->activity->findorFail($activityId)->activity_scope;
    }
}
