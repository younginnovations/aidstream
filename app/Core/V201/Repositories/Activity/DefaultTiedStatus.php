<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class DefaultTiedStatus
 * @package App\Core\V201\Repositories\Activity
 */
class DefaultTiedStatus
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
     * update Default Tied Status
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->default_tied_status = $activityDetails['default_tied_status'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getDefaultTiedStatusData($activityId)
    {
        return $this->activity->findOrFail($activityId)->default_tied_status;
    }
}
