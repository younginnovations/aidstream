<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\Result as V201Result;
use Illuminate\Support\Collection;

/**
 * Class Result
 * @package app\Core\V202\Element\Activity
 */
class Result extends V201Result
{
    /**
     * @param $indicator
     * @return array
     */
    protected function buildIndicator($indicator)
    {
        $indicator = [
            '@attributes' => [
                'measure'   => $indicator[0]['measure'],
                'ascending' => $indicator[0]['ascending']
            ],
            'title'       => [
                'narrative' => $this->buildNarrative($indicator[0]['title'][0]['narrative'])
            ],
            'description' => [
                'narrative' => $this->buildNarrative($indicator[0]['description'][0]['narrative'])
            ],
            'reference'   => getVal($indicator, [0, 'reference']),
            'baseline'    => [
                '@attributes' => [
                    'year'  => $indicator[0]['baseline'][0]['year'],
                    'value' => $indicator[0]['baseline'][0]['value']
                ],
                'comment'     => [
                    'narrative' => $this->buildNarrative($indicator[0]['baseline'][0]['comment'][0]['narrative'])
                ]
            ],
            'period'      => $this->buildPeriod($indicator[0]['period'])
        ];

        return $indicator;
    }

    /**
     * @param $references
     * @return array
     */
    protected function buildReference($references)
    {
        $referenceData = [];
        foreach ($references as $reference) {
            $referenceData[] = [
                '@attributes' => [
                    'vocabulary'    => $reference['vocabulary'],
                    'code'          => $reference['code'],
                    'indicator-uri' => $reference['indicator_uri']
                ]
            ];
        }

        return $referenceData;
    }

    /**
     * @param $data
     * @return array
     */
    protected function buildFunction($data)
    {
        $period = [
            '@attributes' => [
                'value' => $data[0]['value']
            ],
            'location'    => getVal($data, [0, 'location']),
            'dimension'   => getVal($data, [0, 'dimension']),
            'comment'     => [
                'narrative' => $this->buildNarrative(getVal($data, [0, 'comment', 0, 'narrative'], []))
            ]
        ];

        return $period;
    }

    /**
     * @param $locations
     * @return array
     */
    protected function buildLocation($locations)
    {
        $locationData = [];
        foreach ($locations as $location) {
            $locationData[] = [
                '@attributes' => [
                    'ref' => $location['ref']
                ]
            ];
        }

        return $locationData;
    }

    /**
     * @param $dimensions
     * @return array
     */
    protected function buildDimension($dimensions)
    {
        $dimensionData = [];
        foreach ($dimensions as $dimension) {
            $dimensionData[] = [
                '@attributes' => [
                    'name'  => $dimension['name'],
                    'value' => $dimension['value']
                ]
            ];
        }

        return $dimensionData;
    }
}
