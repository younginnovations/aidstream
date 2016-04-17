<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\RecipientRegion as V201RecipientRegion;
use App\Models\Activity\Activity;

/**
 * Class RecipientRegion
 * @package app\Core\V202\Element\Activity
 */
class RecipientRegion extends V201RecipientRegion
{
    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData     = [];
        $recipientRegions = (array) $activity->recipient_region;
        foreach ($recipientRegions as $recipientRegion) {
            $activityData[] = [
                '@attributes' => [
                    'code'           => $recipientRegion['region_code'],
                    'percentage'     => $recipientRegion['percentage'],
                    'vocabulary'     => $recipientRegion['region_vocabulary'],
                    'vocabulary-uri' => getVal($recipientRegion, ['vocabulary_uri'])
                ],
                'narrative'   => $this->buildNarrative($recipientRegion['narrative'])
            ];
        }

        return $activityData;
    }
}
