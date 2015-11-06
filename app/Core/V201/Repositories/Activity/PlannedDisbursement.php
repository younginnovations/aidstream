<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Repositories\Activity
 */
class PlannedDisbursement
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
        $activity->planned_disbursement = $activityDetails['planned_disbursement'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getPlannedDisbursementData($activityId)
    {
        return $this->activity->findorFail($activityId)->planned_disbursement;
    }
}
