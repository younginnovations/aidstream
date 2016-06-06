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
            $point = [];
            if ((getVal($location, ['point', 0, 'position', 0, 'latitude']) != "") && (getVal($location, ['point', 0, 'position', 0, 'longitude']) != "")) {
                $point = [
                    '@attributes' => [
                        'srsName' => getVal($location, ['point', 0, 'srs_name'])
                    ],
                    'pos'         => getVal($location, ['point' ,0 ,'position' ,0 ,'latitude']) . ' ' . getVal($location, ['point', 0, 'position', 0, 'longitude'])
                ];
            }

            $activityData[] = [
                '@attributes'          => [
                    'ref' => getVal($location, ['reference'])
                ],
                'location-reach'       => [
                    '@attributes' => [
                        'code' => getVal($location, ['location_reach', 0, 'code'])
                    ]
                ],
                'location-id'          => [
                    '@attributes' => [
                        'code'       => getVal($location, ['location_id', 0, 'code']),
                        'vocabulary' => getVal($location, ['location_id', 0, 'vocabulary'])
                    ]
                ],
                'name'                 => [
                    'narrative' => $this->buildNarrative(getVal($location, ['name', 0, 'narrative'], []))
                ],
                'description'          => [
                    'narrative' => $this->buildNarrative(getVal($location, ['location_description', 0, 'narrative'], []))
                ],
                'activity-description' => [
                    'narrative' => $this->buildNarrative(getVal($location, ['activity_description', 0, 'narrative'], []))
                ],
                'administrative'       => [
                    '@attributes' => [
                        'code'       => getVal($location, ['administrative', 0, 'code']),
                        'vocabulary' => getVal($location, ['administrative', 0, 'vocabulary']),
                        'level'      => getVal($location, ['administrative', 0, 'level'])
                    ]
                ],
                'point'                => $point,
                'exactness'            => [
                    '@attributes' => [
                        'code' => getVal($location, ['exactness', 0, 'code'])
                    ]
                ],
                'location-class'       => [
                    '@attributes' => [
                        'code' => getVal($location, ['location_class', 0, 'code'])
                    ]
                ],
                'feature-designation'  => [
                    '@attributes' => [
                        'code' => getVal($location, ['feature_designation', 0, 'code'])
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
