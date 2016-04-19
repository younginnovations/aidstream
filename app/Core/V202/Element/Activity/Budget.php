<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\Budget as V201Budget;
use App\Models\Activity\Activity;

/**
 * Class Budget
 * @package App\Core\V202\Element\Activity
 */
class Budget extends V201Budget
{
    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $budgets      = (array) $activity->budget;
        foreach ($budgets as $budget) {
            $activityData[] = [
                '@attributes'  => [
                    'type'   => $budget['budget_type'],
                    'status' => getVal($budget, ['status'])
                ],
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $budget['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $budget['period_end'][0]['date']
                    ]
                ],
                'value'        => [
                    '@attributes' => [
                        'currency'   => $budget['value'][0]['currency'],
                        'value-date' => $budget['value'][0]['value_date']
                    ],
                    '@value'      => $budget['value'][0]['amount']
                ]
            ];
        }

        return $activityData;
    }
}
