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
        return 'App\Core\V201\Forms\Activity\Sectors';
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
            $vocabulary = $sector['sector_vocabulary'];
            if ($vocabulary == 1) {
                $sectorValue = $sector['sector_code'];
            } elseif ($vocabulary == 2) {
                $sectorValue = $sector['sector_category_code'];
            } else {
                $sectorValue = $sector['sector_text'];
            }
            $activityData[] = [
                '@attributes' => [
                    'code'       => $sectorValue,
                    'percentage' => $sector['percentage'],
                    'vocabulary' => $vocabulary
                ],
                'narrative'   => $this->buildNarrative($sector['narrative'])
            ];
        }

        return $activityData;
    }
}
