<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class RelatedActivity
 * @package App\Core\V201\Repositories\Activity
 */
class RelatedActivity
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
        $activity->related_activity = $activityDetails['related_activity'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getRelatedActivityData($activityId)
    {
        return $this->activity->findOrFail($activityId)->related_activity;
    }
}
