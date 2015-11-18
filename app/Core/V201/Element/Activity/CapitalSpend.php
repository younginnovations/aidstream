<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class CapitalSpend
 * @package app\Core\V201\Element\Activity
 */
class CapitalSpend
{
    /**
     * @return Capital Spend form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\CapitalSpend';
    }

    /**
     * @return Capital Spend repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\CapitalSpend');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [
            '@attributes' => [
                'percentage' => $activity['capital_spend']
            ]
        ];

        return $activityData;
    }
}
