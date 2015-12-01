<?php
namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class IatiIdentifierRepository
 * @package App\Core\V201\Repositories\Activity
 */
class IatiIdentifierRepository
{
    /**
     * @param $input
     * @param $activity
     */
    public function update(array $input, Activity $activity)
    {
        unset($input['_token']);
        unset($input['_method']);
        $activity->identifier = $input;
        $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getIatiIdentifierData($activityId)
    {
        return Activity::findorFail($activityId)->identifier;
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getActivityData($activityId)
    {
        return Activity::findorFail($activityId);

    }

    /**
     * get all activity identifiers
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getActivityIdentifiers()
    {
        return Activity::all('identifier');
    }
}
