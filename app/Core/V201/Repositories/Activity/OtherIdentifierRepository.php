<?php
namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

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
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        $activity->other_identifier = $input['otherIdentifier'];

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