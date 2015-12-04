<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class Budget
 * @package App\Core\V201\Element\Activity
 */
class Budget
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Budgets';
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Budget');
    }

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
                    'type' => $budget['budget_type']
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
