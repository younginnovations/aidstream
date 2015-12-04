<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class OtherIdentifier
 * @package app\Core\V201\Element\Activity
 */
class OtherIdentifier extends BaseElement
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleOtherIdentifier";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\OtherIdentifierRepository');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData     = [];
        $otherIdentifiers = (array) $activity->other_identifier;
        foreach ($otherIdentifiers as $otherIdentifier) {
            $activityData [] = [
                '@attributes' => [
                    'ref'  => $otherIdentifier['reference'],
                    'type' => $otherIdentifier['type']
                ],
                'owner-org'   => [
                    '@attributes' => [
                        'ref' => $otherIdentifier['owner_org'][0]['reference']
                    ],
                    'narrative'   => $this->buildNarrative($otherIdentifier['owner_org'][0]['narrative'])
                ]
            ];
        }

        return $activityData;
    }
}
