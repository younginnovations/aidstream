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
     * @param $indicators
     * @return array
     */
    protected function buildIndicator($indicators)
    {
        $indicatorData = [];

        foreach ($indicators as $indicator) {
            $indicatorData[] = [
                '@attributes' => [
                    'measure'   => $indicator['measure'],
                    'ascending' => $indicator['ascending']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative(getVal($indicator, ['title', 0, 'narrative']))
                ],
                'description' => [
                    'narrative' => $this->buildNarrative(getVal($indicator, ['description', 0, 'narrative']))
                ],
                'reference'   => $this->buildReference(getVal($indicator, ['reference'], [])),
                'baseline'    => [
                    '@attributes' => [
                        'year'  => $indicator['baseline'][0]['year'],
                        'value' => $indicator['baseline'][0]['value']
                    ],
                    'comment'     => [
                        'narrative' => $this->buildNarrative(getVal($indicator, ['baseline', 0, 'comment', 0, 'narrative']))
                    ]
                ],
                'period'      => $this->buildPeriod(getVal($indicator, ['period'], []))
            ];
        }

        return $indicatorData;
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
            'location'    => $this->buildLocation(getVal($data, [0, 'location'], [])),
            'dimension'   => $this->buildDimension(getVal($data, [0, 'dimension'], [])),
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
                    'name'  => getVal($dimension, ['name']),
                    'value' => getVal($dimension, ['value'])
                ]
            ];
        }

        return $dimensionData;
    }
}
