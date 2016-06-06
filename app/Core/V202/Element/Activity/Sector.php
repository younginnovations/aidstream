<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\Sector as V201Sector;
use App\Models\Activity\Activity;

/**
 * Class Sector
 * return description form and description repository
 * @package app\Core\V20\Eleme2nt\Activity
 */
class Sector extends V201Sector
{
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
                    'code'           => $sectorValue,
                    'percentage'     => getVal($sector, ['percentage']),
                    'vocabulary'     => $vocabulary,
                    'vocabulary-uri' => getVal($sector, ['vocabulary_uri'])
                ],
                'narrative'   => $this->buildNarrative(getVal($sector, ['narrative'], []))
            ];
        }

        return $activityData;
    }
}
