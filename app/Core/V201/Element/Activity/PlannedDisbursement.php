<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Element\Activity
 */
class PlannedDisbursement
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\PlannedDisbursements';
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\PlannedDisbursement');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData         = [];
        $plannedDisbursements = (array) $activity->planned_disbursement;
        foreach ($plannedDisbursements as $plannedDisbursement) {
            $activityData[] = [
                '@attributes'  => [
                    'type' => $plannedDisbursement['planned_disbursement_type']
                ],
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $plannedDisbursement['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $plannedDisbursement['period_end'][0]['date']
                    ]
                ],
                'value'        => [
                    '@attributes' => [
                        'currency'   => $plannedDisbursement['value'][0]['currency'],
                        'value-date' => $plannedDisbursement['value'][0]['value_date']
                    ],
                    '@value'      => $plannedDisbursement['value'][0]['amount']
                ]
            ];
        }

        return $activityData;
    }
}
