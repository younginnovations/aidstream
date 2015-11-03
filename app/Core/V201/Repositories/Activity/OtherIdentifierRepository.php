<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class OtherIdentifierRepository
 * @package App\Core\V201\Repositories\Activity
 */
class OtherIdentifierRepository
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
     * update other Identifier
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->other_identifier = $activityDetails['other_identifier'];

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
    public function getOtherIdentifierData($activityId)
    {
        return Activity::findorFail($activityId)->other_identifier;
    }
}
