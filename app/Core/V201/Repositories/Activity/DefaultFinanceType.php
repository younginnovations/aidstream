<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class DefaultFinanceType
 * @package App\Core\V201\Repositories\Activity
 */
class DefaultFinanceType
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
     * update Default Finance Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->default_finance_type = $activityDetails['default_finance_type'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getDefaultFinanceTypeData($activityId)
    {
        return $this->activity->findOrFail($activityId)->default_finance_type;
    }
}
