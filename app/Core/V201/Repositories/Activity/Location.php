<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class Location
 * @package App\Core\V201\Repositories\Activity
 */
class Location
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
     * update location
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->location = $activityDetails['location'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getLocation($activityId)
    {
        return $this->activity->findOrFail($activityId)->location;
    }
}
