<?php namespace App\Core\V203\Element\Activity;

use App\Core\Elements\BaseElement;
use Illuminate\Support\Collection;

/**
 * Class Result
 * @package app\Core\V202\Element\Activity
 */
class Result extends BaseElement
{

    /**
     * @return result form path
     */
    public function getForm()
    {
        return 'App\Core\V203\Forms\Activity\Results';
    }

    /**
     * @return result repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Result');
    }

    /**
     * @param $periods
     * @return array
     */
    protected function buildPeriod($periods, $measure = null)
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
                'target'       => $this->buildFunction($period['target'], $measure),
                'actual'       => $this->buildFunction($period['actual'], $measure)
            ];
        }

        return $periodData;
    }

    protected function buildBaseline($baselines, $measure = null)
    {
        $baselineData = [];

        foreach($baselines as $baseline){
            if($measure == 5){
                $baselineValue = NULL;
            } else {
                $baselineValue = $baseline['value'];
            }
            $baselineData[] = [
                '@attributes' => [
                    'year'  => $baseline['year'],
                    'iso-date' => @$baseline['date'],
                    'value' => $baselineValue
                ],
                'location' => @$this->buildLocation($baseline['ref'],[]),
                'dimension' => @$this->buildDimension($baseline['dimension'], $measure),
                'comment'     => [
                    'narrative' => $this->buildNarrative(getVal($baseline, ['comment', 0, 'narrative']))
                ],
            ];
        }

        return $baselineData;
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
                    'ascending' => $indicator['ascending'],
                    'aggregation-status' => (isset($indicator['aggregation_status']) ? $indicator['aggregation_status'] : '')
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative(getVal($indicator, ['title', 0, 'narrative']))
                ],
                'description' => [
                    'narrative' => $this->buildNarrative(getVal($indicator, ['description', 0, 'narrative']))
                ],
                'reference'   => $this->buildReference(getVal($indicator, ['reference'], [])),
                'baseline'    => $this->buildBaseline(getVal($indicator, ['baseline'], []), $indicator['measure']),
                'period'      => $this->buildPeriod(getVal($indicator, ['period'], []), $indicator['measure'])
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
    protected function buildFunction($data, $measure = null)
    {
        $targetData = [];
        foreach($data as $period){
            $targetData[] = [
                '@attributes' => [
                    'value' => $period['value']
                ],
                'location'    => $this->buildLocation(getVal($period, ['location'], [])),
                'dimension'   => $this->buildDimension(getVal($period, ['dimension'], []), $measure),
                'comment'     => [
                    'narrative' => $this->buildNarrative(getVal($period, ['comment', 0, 'narrative'], []))
                ]
            ];
        }

        return $targetData;
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
    protected function buildDimension($dimensions, $measure = null)
    {
        $dimensionData = [];
        foreach ($dimensions as $dimension) {
            if($measure == 5){
                $dimensionValue = NULL;
            } else {
                $dimensionValue = getVal($dimension, ['value']);
            }
            $dimensionData[] = [
                '@attributes' => [
                    'name'  => getVal($dimension, ['name']),
                    'value' => $dimensionValue
                ]
            ];
        }

        return $dimensionData;
    }

    protected function buildDocumentLink($documentLinks)
    {
        $documentLinkData = [];

        foreach ($documentLinks as $documentLink) {

            $documentLinkData[] = [
                '@attributes' => [
                    'url'    => $documentLink['url'],
                    'format' => $documentLink['format']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative($documentLink['title'][0]['narrative'])
                ],
                'description' => [
                    'narrative' => $this->buildNarrative($documentLink['description'][0]['narrative'])
                ],
                'category'    => [
                    '@attributes' => [
                        'code' => isset($documentLink['category'][0]['code']) ? $documentLink['category'][0]['code'] : ''
                    ]
                ],
                'language'    => [
                    '@attributes' => [
                        'code' => isset($documentLink['language'][0]['language']) ? $documentLink['language'][0]['language'] : ''
                    ]
                ],
                'document-date' => [
                    '@attributes' => [
                        'iso-date' => getVal($documentLink, ['document_date', 0, 'date'])
                    ]
                ]
            ];
        }

        return $documentLinkData;
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
                'document-link' => $this->buildDocumentLink((isset($result['document_link']) ? $result['document_link'] : [])),
                'reference' => [
                    '@attributes' => [
                        'vocabulary' => @$result['reference'][0]['vocabulary'],
                        'code' => @$result['reference'][0]['code'],
                        'vocabulary-uri' => @$result['reference'][0]['vocabulary_uri']
                    ],
                ],
                'indicator'   => $this->buildIndicator($result['indicator'])
            ];
        }

        return $resultData;
    }
}
