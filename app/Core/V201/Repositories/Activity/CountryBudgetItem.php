<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class CountryBudgetItem
 * @package App\Core\V201\Repositories\Activity
 */
class CountryBudgetItem
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
     * update Country Budget Item
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->country_budget_items = $activityDetails['country_budget_item'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getCountryBudgetItemData($activityId)
    {
        return $this->activity->findOrFail($activityId)->country_budget_items;
    }
}
