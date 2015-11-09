<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class CapitalSpend
 * @package App\Core\V201\Repositories\Activity
 */
class CapitalSpend
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
     * update Capital Spend
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->capital_spend = $activityDetails['capital_spend'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getCapitalSpendData($activityId)
    {
        return $this->activity->findOrFail($activityId)->capital_spend;
    }
}
