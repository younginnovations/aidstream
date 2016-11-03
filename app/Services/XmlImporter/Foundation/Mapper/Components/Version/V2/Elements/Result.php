<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements;

use App\Services\XmlImporter\Foundation\Support\Helpers\Traits\XmlHelper;

/**
 * Class Result
 * @package App\Services\XmlImporter\Mapper\V103\Activity\Elements
 */
class Result
{
    use XmlHelper;

    /**
     * @var
     */
    protected $result;
    /**
     * @var
     */
    protected $indicators;

    /**
     * @param array $results
     * @param       $template
     */
    public function map(array $results, $template)
    {
        foreach ($results as $index => $result) {
            $this->result[$index]                                = $template['result'];
            $this->result[$index]['type']                        = $this->attributes($result, 'type');
            $this->result[$index]['aggregation_status']          = $this->attributes($result, 'aggregation-status');
            $this->result[$index]['title'][0]['narrative']       = $this->value(getVal($result, ['value'], []), 'title');
            $this->result[$index]['description'][0]['narrative'] = $this->value(getVal($result, ['value'], []), 'description');
            $this->result[$index]['indicator']                   = $this->indicator($result, $index);
        }

        return $this->result;
    }

    /**
     * @param $result
     * @param $index
     * @return array
     */
    protected function indicator($result, $index)
    {
        $indicatorAttributes = $this->filterAttributes(getVal($result, ['value'], []), 'indicator', ['measure', 'ascending']);
        $indicators          = $this->filterValues(getVal($result, ['value'], []), 'indicator');
        $indicatorTemplate   = getVal($this->result[$index], ['indicator'], []);

        $indicatorData = [getVal($indicatorTemplate, [0], [])];
        foreach ($indicators as $index => $indicator) {
            $indicator                                            = $indicator['indicator'];
            $indicatorData[$index]['measure']                     = getVal($indicatorAttributes, [$index, 'measure'], '');
            $indicatorData[$index]['ascending']                   = getVal($indicatorAttributes, [$index, 'ascending'], '');
            $indicatorData[$index]['title'][0]['narrative']       = $this->value($indicator, 'title');
            $indicatorData[$index]['description'][0]['narrative'] = $this->value($indicator, 'description');
            $indicatorData[$index]['reference']                   = $this->reference($indicator, $indicatorTemplate);
            $indicatorData[$index]['baseline']                    = $this->baseline($indicator, $indicatorTemplate);
            $indicatorData[$index]['period']                      = $this->period($indicator, $indicatorTemplate);
        }

        return $indicatorData;
    }

    /**
     * @param $indicator
     * @param $indicatorTemplate
     * @return string
     */
    protected function reference($indicator, $indicatorTemplate)
    {
        $references    = $this->filterAttributes($indicator, 'reference', ['vocabulary', 'code', 'indicator_uri']);
        $referenceData = getVal($indicatorTemplate, [0, 'reference']);
        foreach ($references as $referenceIndex => $reference) {
            $referenceData[$referenceIndex] = $reference;
        }

        return $referenceData;
    }

    /**
     * @param $indicator
     * @param $indicatorTemplate
     * @return string
     */
    protected function baseline($indicator, $indicatorTemplate)
    {
        $baseline                               = getVal($indicatorTemplate, [0, 'baseline']);
        $baselineAttributes                     = $this->filterAttributes($indicator, 'baseline', ['year', 'value']);
        $baseline[0]['year']                    = getVal($baselineAttributes, [0, 'year'], '');
        $baseline[0]['value']                   = getVal($baselineAttributes, [0, 'value'], '');
        $baselineValues                         = getVal($this->filterValues($indicator, 'baseline'), [0, 'baseline', 0]);
        $baseline[0]['comment'][0]['narrative'] = $this->narrative($baselineValues);

        return $baseline;
    }

    /**
     * @param $indicator
     * @param $indicatorTemplate
     * @return string
     */
    protected function period($indicator, $indicatorTemplate)
    {
        $periods     = $this->filterValues($indicator, 'period');
        $periodsData = getVal($indicatorTemplate, [0, 'period']);
        foreach ($periods as $index => $period) {
            $period                                         = getVal($period, ['period'], []);
            $periodsData[$index]['period_start'][0]['date'] = getVal($this->filterAttributes($period, 'periodStart', ['iso-date']), [0, 'iso-date'], '');
            $periodsData[$index]['period_end'][0]['date']   = getVal($this->filterAttributes($period, 'periodEnd', ['iso-date']), [0, 'iso-date'], '');
            $periodsData[$index]['target']                  = $this->target($period, $periodsData);
            $periodsData[$index]['actual']                  = $this->actual($period, $periodsData);

        }

        return $periodsData;
    }

    /**
     * @param $period
     * @param $periodTemplate
     * @return string
     */
    protected function target($period, $periodTemplate)
    {
        $targetData                 = getVal($periodTemplate, [0, 'target']);
        $targetData[0]['value']     = getVal($this->filterAttributes($period, 'target', ['value']), [0, 'value'], '');
        $target                     = getVal($this->filterValues($period, 'target'), [0, 'target'], []);
        $targetData[0]['location']  = $this->location($target, $targetData);
        $targetData[0]['dimension'] = $this->dimension($target, $targetData);
        $targetData[0]['comment']   = $this->comment($target, $targetData);

        return $targetData;
    }

    /**
     * @param $period
     * @param $periodTemplate
     * @return string
     */
    protected function actual($period, $periodTemplate)
    {
        $actualData                 = getVal($periodTemplate, [0, 'actual']);
        $actualData[0]['value']     = getVal($this->filterAttributes($period, 'actual', ['value']), [0, 'value'], '');
        $actual                     = getVal($this->filterValues($period, 'actual'), [0, 'actual'], []);
        $actualData[0]['location']  = $this->location($actual, $actualData);
        $actualData[0]['dimension'] = $this->dimension($actual, $actualData);
        $actualData[0]['comment']   = $this->comment($actual, $actualData);

        return $actualData;
    }

    /**
     * @param $data
     * @param $template
     * @return string
     */
    protected function location($data, $template)
    {
        $locationData = getVal($template, [0, 'location'], []);
        $locations    = $this->filterAttributes($data, 'location', ['ref']);
        foreach ($locations as $index => $location) {
            $locationData[$index]['ref'] = getVal($location, ['ref']);
        }

        return $locationData;
    }

    /**
     * @param $data
     * @param $template
     * @return string
     */
    protected function dimension($data, $template)
    {
        $dimensionData = getVal($template, [0, 'dimension'], []);
        $dimensions    = $this->filterAttributes($data, 'dimension', ['name', 'value']);
        foreach ($dimensions as $index => $dimension) {
            $dimensionData[$index]['name']  = getVal($dimension, ['name']);
            $dimensionData[$index]['value'] = getVal($dimension, ['value']);
        }

        return $dimensionData;
    }

    /**
     * @param $data
     * @param $template
     * @return string
     */
    protected function comment($data, $template)
    {
        $commentData = getVal($template, [0, 'comment'], []);
        $comments    = getVal($this->filterValues($data, 'comment'), [0, 'comment'], []);
        foreach ($comments as $index => $comment) {
            $commentData[0]['narrative'][$index] = getVal($this->narrative($comment), [0]);
        }

        return $commentData;
    }
}
