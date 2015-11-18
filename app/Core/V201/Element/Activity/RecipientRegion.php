<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class RecipientRegion
 * @package app\Core\V201\Element\Activity
 */
class RecipientRegion extends BaseElement
{
    /**
     * @return recipient region form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleRecipientRegion";
    }

    /**
     * @return recipient region repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\RecipientRegion');
    }

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
                    'code'       => $recipientRegion['region_code'],
                    'percentage' => $recipientRegion['percentage'],
                    'vocabulary' => $recipientRegion['region_vocabulary']
                ],
                'narrative'   => $this->buildNarrative($recipientRegion['narrative'])
            ];
        }

        return $activityData;
    }
}
