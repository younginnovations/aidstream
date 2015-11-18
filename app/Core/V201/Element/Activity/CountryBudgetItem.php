<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class CountryBudgetItem
 * @package app\Core\V201\Element\Activity
 */
class CountryBudgetItem extends BaseElement
{
    /**
     * @return  country Budget Item form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\CountryBudgetItems";
    }

    /**
     * @return country Budget Item repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\CountryBudgetItem');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData      = [];
        $countryBudgetItem = (array) $activity->country_budget_items;
        foreach ($countryBudgetItem as $CountryBudgetItem) {
            $activityData[] = [
                '@attributes' => [
                    'vocabulary' => $CountryBudgetItem['vocabulary']
                ],
                'budget-item' => [
                    '@attributes' => [
                        'code'       => $CountryBudgetItem['budget_item'][0]['code'],
                        'percentage' => $CountryBudgetItem['budget_item'][0]['percentage']
                    ],
                    'description' => [
                        'narrative' => $this->buildNarrative($CountryBudgetItem['budget_item'][0]['description'][0]['narrative'])
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
