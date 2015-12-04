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
     * @param $indicator
     * @return array
     */
    private function buildIndicator($indicator)
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
     * @param $period
     * @return array
     */
    private function buildPeriod($period)
    {
        $period = [
            'period-start' => [
                '@attributes' => [
                    'iso-date' => $period[0]['period_start'][0]['date']
                ]
            ],
            'period-end'   => [
                '@attributes' => [
                    'iso-date' => $period[0]['period_end'][0]['date']
                ]
            ],
            'target'       => $this->buildFunction($period[0]['target']),
            'actual'       => $this->buildFunction($period[0]['actual'])
        ];

        return $period;
    }

    /**
     * @param $data
     * @return array
     */
    private function buildFunction($data)
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
