<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use Illuminate\Support\Collection;

/**
 * Class Result
 * @package app\Core\V201\Element\Activity
 */
class Result extends BaseElement
{
    /**
     * @return result form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Results';
    }

    /**
     * @return result repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Result');
    }

    /**
     * @param $results
     * @return array
     */
    public function getXmlData(Collection $results)
    {
        $resultData = [];

        foreach ($results as $totalResult) {
            $result       = $totalResult->result;
            $resultData[] = [
                '@attributes' => [
                    'type'               => $result['type'],
                    'aggregation-status' => $result['aggregation_status']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative($result['title'][0]['narrative'])
                ],
                'description' => [
                    'narrative' => $this->buildNarrative($result['description'][0]['narrative'])
                ],
                'indicator'   => $this->buildIndicator($result['indicator'])
            ];
        }

        return $resultData;
    }

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
                'baseline'    => [
                    '@attributes' => [
                        'year'  => getVal($indicator, ['baseline', 0, 'year']),
                        'value' => getVal($indicator, ['baseline', 0, 'value'], '0')
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
     * @param $periods
     * @return array
     */
    protected function buildPeriod($periods)
    {
        $periodData = [];

        foreach ($periods as $period) {
            $periodData[] = [
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $period['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $period['period_end'][0]['date']
                    ]
                ],
                'target'       => $this->buildFunction($period['target']),
                'actual'       => $this->buildFunction($period['actual'])
            ];
        }

        return $periodData;
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
            'comment'     => [
                'narrative' => $this->buildNarrative($data[0]['comment'][0]['narrative'])
            ]
        ];

        return $period;
    }
}
