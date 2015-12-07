<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class Location
 * @package app\Core\V201\Element\Activity
 */
class Location extends BaseElement
{
    /**
     * @return location form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Locations";
    }

    /**
     * @return location repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Location');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $locations    = (array) $activity->location;
        foreach ($locations as $location) {
            $activityData[] = [
                '@attributes'          => [
                    'ref' => $location['reference']
                ],
                'location-reach'       => [
                    '@attributes' => [
                        'code' => $location['location_reach'][0]['code']
                    ]
                ],
                'location-id'          => [
                    '@attributes' => [
                        'code'       => $location['location_id'][0]['code'],
                        'vocabulary' => $location['location_id'][0]['vocabulary']
                    ]
                ],
                'name'                 => [
                    'narrative' => $this->buildNarrative($location['name'][0]['narrative'])
                ],
                'description'          => [
                    'narrative' => $this->buildNarrative($location['location_description'][0]['narrative'])
                ],
                'activity-description' => [
                    'narrative' => $this->buildNarrative($location['activity_description'][0]['narrative'])
                ],
                'administrative'       => [
                    '@attributes' => [
                        'code'       => $location['administrative'][0]['code'],
                        'vocabulary' => $location['administrative'][0]['vocabulary'],
                        'level'      => $location['administrative'][0]['level']
                    ]
                ],
                'point'                => [
                    '@attributes' => [
                        'srsName' => $location['point'][0]['srs_name']
                    ],
                    'pos'         => $location['point'][0]['position'][0]['latitude'] . ' ' . $location['point'][0]['position'][0]['longitude']

                ],
                'exactness'            => [
                    '@attributes' => [
                        'code' => $location['exactness'][0]['code']
                    ]
                ],
                'location-class'       => [
                    '@attributes' => [
                        'code' => $location['location_class'][0]['code']
                    ]
                ],
                'feature-designation'  => [
                    '@attributes' => [
                        'code' => $location['feature_designation'][0]['code']
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
