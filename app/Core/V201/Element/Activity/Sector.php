<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class Sector
 * return description form and description repository
 * @package app\Core\V201\Element\Activity
 */
class Sector extends BaseElement
{
    /**
     * @return sector form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Sectors";
    }

    /**
     * @return sector repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Sector');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $sectors      = (array) $activity->sector;
        foreach ($sectors as $sector) {
            $activityData[] = [
                '@attributes' => [
                    'code'       => $sector['sector_select'],
                    'percentage' => $sector['percentage'],
                    'vocabulary' => $sector['vocabulary']
                ],
                'narrative'   => $this->buildNarrative($sector['narrative'])
            ];
        }

        return $activityData;
    }
}
