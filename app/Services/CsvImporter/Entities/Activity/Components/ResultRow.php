<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;
use App\Services\CsvImporter\Entities\Row;

/**
 * Class ResultRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ResultRow extends Row
{

    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp/result';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

    /**
     *
     */
    const RESULT_TEMPLATE_FILE = '/Services/CsvImporter/Entities/Activity/Components/Elements/Foundation/Template/Result.json';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var
     */
    protected $fields;

    /**
     * @var array
     */
    protected $indicators = [];

    /**
     * @var array
     */
    protected $resultFields = [
        'type',
        'aggregation_status',
        'title',
        'title_language',
        'description',
        'description_language',
        'reference' => [
            'vocabulary',
            'code',
            'vocabulary_uri'
        ],
        'indicator' => [
            'measure',
            'ascending',
            'indicator_aggregation_status',
            'indicator_title',
            'indicator_title_language',
            'indicator_description',
            'indicator_description_language',
            'reference_vocabulary',
            'reference_code',
            'reference_uri',
            'baseline_year',
            'baseline_value',
            'baseline_location_ref',
            'baseline_dimension_name',
            'baseline_dimension_value',
            'baseline_comment',
            'baseline_comment_language',
            'period_start',
            'period_end',
            'target_value',
            'target_location_ref',
            'target_dimension_name',
            'target_dimension_value',
            'target_comment',
            'target_comment_language',
            'actual_value',
            'actual_location_ref',
            'actual_dimension_name',
            'actual_dimension_value',
            'actual_comment',
            'actual_comment_language'
        ]
    ];

    /**
     * @var array
     */
    protected $resultTemplate = [
        'type',
        'aggregation_status',
        'title',
        'title_language',
        'description',
        'description_language',
        'results_reference_vocabulary',
        'results_reference_code',
        'results_reference_uri',
        'indicator',
        'measure',
        'ascending',
        'indicator_aggregation_status',
        'indicator_title',
        'indicator_title_language',
        'indicator_description',
        'indicator_description_language',
        'reference_vocabulary',
        'reference_code',
        'reference_uri',
        'baseline_year',
        'baseline_value',
        'baseline_location_ref',
        'baseline_dimension_name',
        'baseline_dimension_value',
        'baseline_comment',
        'baseline_comment_language',
        'period_start',
        'period_end',
        'target_value',
        'target_location_ref',
        'target_dimension_name',
        'target_dimension_value',
        'target_comment',
        'target_comment_language',
        'actual_value',
        'actual_location_ref',
        'actual_dimension_name',
        'actual_dimension_value',
        'actual_comment',
        'actual_comment_language'
    ];

    /**
     * @var array
     */
    protected $periodFields = [
        'period_start',
        'period_end',
        'target_value',
        'target_location_ref',
        'target_dimension_name',
        'target_dimension_value',
        'target_comment',
        'target_comment_language',
        'actual_value',
        'actual_location_ref',
        'actual_dimension_name',
        'actual_dimension_value',
        'actual_comment',
        'actual_comment_language'
    ];

    /**
     * @var array
     */
    protected $baselineFields = [
        'baseline_year',
        'baseline_value',
        'baseline_location_ref',
        'baseline_dimension_name',
        'baseline_dimension_value',
        'baseline_comment',
        'baseline_comment_language'
    ];

    /**
     * @var
     */
    protected $validator;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var Validation
     */
    protected $factory;

    /**
     * @var array
     */
    protected $validElements = [];

    /**
     * @var
     */
    protected $organizationId;

    /**
     * @var
     */
    protected $userId;

    /**
     * @var
     */
    protected $template;

    /**
     * @var
     */
    protected $narrative;

    /**
     * System Version
     *
     * @var String
     */
    protected $version;

    /**
     * ResultRow constructor.
     * @param            $fields
     * @param            $organizationId
     * @param            $userId
     * @param Validation $factory
     */
    public function __construct($fields, $organizationId, $userId, $version, Validation $factory)
    {
        $this->fields         = $fields;
        $this->organizationId = $organizationId;
        $this->userId         = $userId;
        $this->factory        = $factory;
        $this->version        = $version;
    }

    /**
     * Group rows into single Result Indicator.
     */
    protected function groupValues()
    {
        $index = - 1;

        $this->indicators[0]['measure'][0]   = '';
        $this->indicators[0]['ascending'][0] = '';

        foreach ($this->fields['measure'] as $i => $row) {

            if (!$this->isSameEntity($i)) {

                $index ++;

                $this->indicators[$index]['measure'][0]   = '';
                $this->indicators[$index]['ascending'][0] = '';
            }

            if ($index >= 0) {
                $this->setValue($index, $i);
            }
        }
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     * @param $i
     */
    protected function setValue($index, $i)
    {
        foreach ($this->fields() as $row => $value) {
            if (array_key_exists($row, array_flip($this->resultFields['indicator']))) {
                $this->indicators[$index][$row][] = $value[$i];
            }
        }
    }

    /**
     * Check if the next row is new row or not.
     * @param $i
     * @return bool
     */
    protected function isSameEntity($i)
    {
        if ((is_null($this->fields[$this->resultFields['indicator'][0]][$i]) || $this->fields[$this->resultFields['indicator'][0]][$i] == '')
            && (is_null($this->fields[$this->resultFields['indicator'][1]][$i]) || $this->fields[$this->resultFields['indicator'][1]][$i] == '')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Load Result Template
     */
    protected function loadTemplate()
    {
        $path            = app_path(self::RESULT_TEMPLATE_FILE);
        $this->template  = json_decode(file_get_contents($path), true);
        $this->data      = json_decode(file_get_contents($path), true);
        $this->narrative = $this->template['title'][0];
    }

    /**
     * Maps entire Row
     * @return $this
     * @internal param $fields
     */
    public function mapResultRow()
    {
        $this->loadTemplate();
        $this->beginMapping();

        return $this;
    }

    /**
     * Begins mapping
     */
    protected function beginMapping()
    {
        $this->setType()
             ->setAggregationStatus()
             ->setTitle()
             ->setDescription()
             ->setReference()
             ->setIndicator();
    }

    /**
     * Maps Result Type
     * @return $this
     */
    protected function setType()
    {
        $value = getVal($this->fields, ['type'], null);
        if (!is_null($value)) {
            $this->data['type'] = $value[0];
        }

        return $this;
    }

    /**
     * Maps Result Aggregation Status
     * @return $this
     */
    protected function setAggregationStatus()
    {
        $values = getVal($this->fields, ['aggregation_status'], null);

        if (!is_null($values[0])) {
            $value                            = $this->isBoolean($values[0]);
            $this->data['aggregation_status'] = $value;
        }

        return $this;
    }

    /**
     * Maps Result Title
     * @return $this
     */
    protected function setTitle()
    {
        $this->setNarrative(['title'], 'title', 'title_language');

        return $this;
    }

    /**
     * Maps Result Description
     * @return $this
     */
    protected function setDescription()
    {
        $this->setNarrative(['description'], 'description', 'description_language');

        return $this;
    }

    /**
     * Maps Result Indicators
     * @return $this
     */
    protected function setIndicator()
    {
        $this->groupIndicator();

        foreach ($this->indicators as $index => $values) {
            $this->data['indicator'][$index] = getVal($this->template, ['indicator', 0]);
            $this->setIndicatorMeasure($index)
                 ->setIndicatorAscending($index)
                 ->setIndicatorTitle($index)
                 ->setIndicatorDescription($index)
                 ->setReferenceVocabulary($index)
                 ->setReferenceCode($index)
                 ->setReferenceURI($index)
                 ->setIndicatorBaseline($index)
                 ->setIndicatorPeriod($index);
        }

        return $this;
    }

    protected function setReference()
    {
        if($this->version !== 'V203') {
            return $this;
        }
        $referenceVocabulary = getVal($this->fields, ['results_reference_vocabulary'], null);
        $referenceCode       = getVal($this->fields, ['results_reference_code'], null);
        $referenceUri        = getVal($this->fields, ['results_reference_uri'], null);

        $this->data['reference'][0]['vocabulary']     = $referenceVocabulary[0];
        $this->data['reference'][0]['code']           = $referenceCode[0];
        $this->data['reference'][0]['vocabulary_uri'] = $referenceUri[0];

        return $this;
    }


    /**
     * Grouping of Result Indicators
     */
    protected function groupIndicator()
    {
        $this->groupValues();
    }

    /**
     * Maps Result Indicator Measure
     * @param $index
     * @return $this
     */
    protected function setIndicatorMeasure($index)
    {
        $measure = getVal($this->indicators[$index], ['measure'], null);

        if (!is_null($measure)) {
            foreach ($measure as $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['measure'] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Ascending
     * @param $index
     * @return $this
     */
    protected function setIndicatorAscending($index)
    {
        $values = getVal($this->indicators[$index], ['ascending'], null);

        if (!is_null($values)) {
            foreach ($values as $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['ascending'] = $this->isBoolean($value);
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Title
     * @param $index
     * @return $this
     */
    protected function setIndicatorTitle($index)
    {
        $this->setNarrative(['indicator', $index, 'title'], 'indicator_title', 'indicator_title_language', $this->indicators[$index]);

        return $this;
    }

    /**
     * Maps Result Indicator Description
     * @param $index
     * @return $this
     */
    protected function setIndicatorDescription($index)
    {
        $this->setNarrative(['indicator', $index, 'description'], 'indicator_description', 'indicator_description_language', $this->indicators[$index]);

        return $this;
    }

    /**
     * Maps Result Reference Vocabulary
     * @param $index
     * @return $this
     */
    protected function setReferenceVocabulary($index)
    {
        $values = getVal($this->indicators[$index], ['reference_vocabulary'], null);

        if (!is_null($values)) {
            foreach ($values as $i => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['reference'][$i]['vocabulary'] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Reference Code
     * @param $index
     * @return $this
     */
    protected function setReferenceCode($index)
    {
        $values = getVal($this->indicators[$index], ['reference_code'], null);

        if (!is_null($values)) {
            foreach ($values as $i => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['reference'][$i]['code'] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Reference URI
     * @param $index
     * @return $this
     */
    protected function setReferenceURI($index)
    {
        $values = getVal($this->indicators[$index], ['reference_uri'], null);

        if (!is_null($values)) {
            foreach ($values as $i => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['reference'][$i]['indicator_uri'] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Baseline
     * @param IndicatorIndex $index
     * @return $this
     */
    protected function setIndicatorBaseline($index)
    {
        if($this->version == 'V203') {
            $this->groupBaseline();
            foreach(getVal($this->indicators, [$index, 'baseline'], []) as $i => $value) {
                $this->setIndicatorBaseLineYearMultiple($index, $i)
                     ->setIndicatorBaselineValueMultiple($index, $i)
                     ->setIndicatorBaselineCommentMultiple($index, $i)
                     ->setIndicatorBaselineLocationMultiple($index, $i)
                     ->setIndicatorBaselineDimensionNameMultiple($index, $i)
                     ->setIndicatorBaselineDimensionValueMultiple($index, $i);
            }

            return $this;
        }

        $this->setIndicatorBaselineYear($index)
             ->setIndicatorBaselineValue($index)
             ->setIndicatorBaselineComment($index)
             ->setIndicatorBaselineLocation($index)
             ->setIndicatorBaselineDimensionName($index)
             ->setIndicatorBaselineDimensionValue($index);

        return $this;
    }

    protected function setIndicatorBaselineYearMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_year'], []);

        foreach($values as $key => $value) {
            if(!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][$i]['year'] = (string) $value;
            }
        }

        return $this;
    }

    protected function setIndicatorBaselineValueMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_value'], []);

        foreach($values as $key => $value) {
            if(!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][$i]['value'] = (string) $value;
            }
        }

        return $this;
    }

    protected function setIndicatorBaselineDimensionNameMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_dimension_name'], []);

        foreach($values as $key => $value) {
            if(!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][$i]['dimension'][$key]['name'] = (string) $value;
            }
        }

        return $this;
    }

    protected function setIndicatorBaselineDimensionValueMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_dimension_value'], []);

        foreach($values as $key => $value) {
            if(!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][$i]['dimension'][$key]['value'] = (string) $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Baseline Comment
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineCommentMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_comment'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {

                    $this->data['indicator'][$index]['baseline'][$i]['comment'][0] = $this->narrative;
                    $this->setNarrative(['indicator', $index, 'baseline', $i, 'comment'], 'baseline_comment', 'baseline_comment_language', $this->indicators[$index]['baseline'][$i]);
                }
            }
        }

        return $this;
    }

        /**
     * Maps Result Indicator Baseline Location
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineLocationMultiple($index, $i)
    {
        $values = getVal($this->indicators[$index], ['baseline', $i, 'baseline_location_ref'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$i]['location_ref'] = (string) $value;
                }
            }
        }

        return $this;
    }


    /**
     * Maps Result Indicator Baseline Year
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineYear($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_year'], null);

        if (!is_null($values)) {
            foreach ($values as $i => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$i]['year'] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Baseline Value
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineValue($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_value'], null);

        if (!is_null($values)) {
            foreach ($values as $i => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$i]['value'] = (string) $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Baseline Comment
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineComment($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_comment'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][0]['comment'][$baselineIndex] = $this->narrative;
                    $this->setNarrative(['indicator', $index, 'baseline', 0, 'comment'], 'baseline_comment', 'baseline_comment_language', $this->indicators[$index]);
                }
            }
        }

        return $this;
    }

        /**
     * Maps Result Indicator Baseline Location
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineLocation($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_location_ref'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$baselineIndex]['location_ref'] = (string) $value;
                }
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Baseline Dimension Name
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineDimensionName($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_dimension_name'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$baselineIndex]['dimension']['name'] = (string) $value;
                }
            }
        }

        return $this;
    }

        /**
     * Maps Result Indicator Baseline Dimension Value
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineDimensionValue($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_dimension_value'], []);

        if (!is_null($values)) {
            foreach ($values as $baselineIndex => $value) {
                if (!is_null($value)) {
                    $this->data['indicator'][$index]['baseline'][$baselineIndex]['dimension']['value'] = (string) $value;
                }
            }
        }

        return $this;
    }


    /**
     * Maps Result Indicator Period
     * @param $index
     * @return $this
     */
    protected function setIndicatorPeriod($index)
    {
        $this->groupPeriods();
        
        foreach (getVal($this->indicators, [$index, 'period'], []) as $i => $value) {
            $this->data['indicator'][$index]['period'][$i] = getVal($this->template, ['indicator', 0, 'period', 0]);
            $this->setIndicatorPeriodStart($index, $i)
                 ->setIndicatorPeriodEnd($index, $i)
                 ->setIndicatorPeriodTarget($index, $i)
                 ->setIndicatorPeriodActual($index, $i);
        }


        return $this;
    }

    /**
     * Grouping of Result Indicator Periods
     */
    protected function groupPeriods()
    {
        
        foreach ($this->indicators as $indicatorIndex => $values) {
            if (!array_diff_key(array_flip($this->periodFields), $this->indicators[$indicatorIndex])) {
                $grouping                                    = app()->make(Grouping::class, [$this->indicators[$indicatorIndex], $this->periodFields])->groupValues();
                $this->indicators[$indicatorIndex]['period'] = $grouping;
            }
        }

    }

    protected function groupBaseline()
    {
        foreach($this->indicators as $indicatorIndex => $values) {
            if(!array_diff_key(array_flip($this->baselineFields), $this->indicators[$indicatorIndex])) {
                $grouping                                      = app()->make(Grouping::class, [$this->indicators[$indicatorIndex], $this->baselineFields])->groupValues();
                $this->indicators[$indicatorIndex]['baseline'] = $grouping;
            }
        }
    }

    /**
     * Maps Result Indicator Period Start
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodStart($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'period_start'], []);

        foreach ($values as $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['period_start'][0]['date'] = dateFormat('Y-m-d', $value);
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period End
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodEnd($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'period_end'], []);


        foreach ($values as $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['period_end'][0]['date'] = dateFormat('Y-m-d', $value);
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Target
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTarget($index, $i)
    {
        $this->setIndicatorPeriodTargetValue($index, $i)
             ->setIndicatorPeriodTargetLocationRef($index, $i)
             ->setIndicatorPeriodTargetDimensionName($index, $i)
             ->setIndicatorPeriodTargetDimensionValue($index, $i)
             ->setIndicatorPeriodTargetComment($index, $i);

        return $this;
    }

    /**
     * Maps Result Indicator Period Target Value
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetValue($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'target_value'], []);

        foreach ($values as $key => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][$key]['value'] = (string) $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Target Location Reference
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'target_location_ref'], []);

        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['location'][$locationIndex]['ref'] = $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Target Dimension Name
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'target_dimension_name'], []);

        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['dimension'][$dIndex]['name'] = $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Target Dimension Value
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'target_dimension_value'], []);

        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['dimension'][$dIndex]['value'] = $value;
            }
        }


        return $this;
    }

    /**
     * Maps Result Indicator Period Target Comment
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetComment($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'target_comment'], []);

        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['comment'][$cIndex] = $this->narrative;
                $this->setNarrative(['indicator', $index, 'period', $i, 'target', 0, 'comment'], 'target_comment', 'target_comment_language', $this->indicators[$index]['period'][$i]);
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActual($index, $i)
    {
        $this->setIndicatorPeriodActualValue($index, $i)
             ->setIndicatorPeriodActualLocationRef($index, $i)
             ->setIndicatorPeriodActualDimensionName($index, $i)
             ->setIndicatorPeriodActualDimensionValue($index, $i)
             ->setIndicatorPeriodActualComment($index, $i);

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual Value
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualValue($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'actual_value'], []);
        foreach ($values as $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['value'] = (string) $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual Location Reference
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'actual_location_ref'], []);

        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['location'][$locationIndex]['ref'] = $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual Dimension Name
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'actual_dimension_name'], []);

        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['dimension'][$dIndex]['name'] = $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual Dimension Value.
     *
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'actual_dimension_value'], []);

        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['dimension'][$dIndex]['value'] = $value;
            }
        }

        return $this;
    }

    /**
     * Maps Result Indicator Period Actual Comment.
     *
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualComment($index, $i)
    {
        $values = getVal($this->indicators[$index], ['period', $i, 'actual_comment'], []);

        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['comment'][$cIndex] = $this->narrative;
                $this->setNarrative(['indicator', $index, 'period', $i, 'actual', 0, 'comment'], 'actual_comment', 'actual_comment_language', $this->indicators[$index]['period'][$i]);
            }
        }

        return $this;
    }

    /**
     * Maps the data with narrative and language.
     *
     * @param array $key
     * @param       $narrativeKey
     * @param       $languageKey
     * @param null  $fields
     */
    protected function setNarrative(array $key, $narrativeKey, $languageKey, $fields = null)
    {
        if (is_null($fields)) {
            $data = $this->fields();
        } else {
            $data = $fields;
        }

        $narrative = getVal($data, [$narrativeKey], []);
        $language  = getVal($data, [$languageKey], []);

        foreach ($narrative as $index => $value) {

            if (!is_null($value)) {
                array_set($this->data, implode('.', [implode('.', $key), 0, 'narrative', $index, 'narrative']), $value);
            }
        }

        foreach ($language as $index => $value) {

            if (!is_null($value)) {
                array_set($this->data, implode('.', [implode('.', $key), 0, 'narrative', $index, 'language']), strtolower($value));
            }
        }
    }

    /**
     * Process the Row.
     * @return $this
     */
    public function process()
    {
        //
    }

    /**
     * Validate the Row.
     * @return $this
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())->with($this->rules(), $this->messages())->getValidatorInstance();

        $this->setValidity();

        $this->recordErrors();
        return $this;
    }

    /**
     * Store the Row in a temporary JSON File for further usage.
     */
    public function keep()
    {
        $this->makeDirectoryIfNonExistent()
             ->writeCsvDataAsJson($this->getCsvFilepath());
    }

    /**
     * @return array
     */
    protected function data()
    {
        return $this->data;
    }

    /**
     * Provides the rules for the IATI Element validation.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if($this->version == 'V203') {
        foreach($this->data['reference'] as $referenceIndex => $references){
            $refIndex = $this->data['reference'][$referenceIndex]['vocabulary'];  
            if($refIndex == 99){
                $rules['reference.' . $referenceIndex . '.indicator_uri'] = sprintf(
                    'url|required_with:%s', 'reference.' . $referenceIndex .  '.vocabulary'
                );
            }
        }
    }

        foreach ($this->data['indicator'] as $indicatorIndex => $indicators) {
            $rules['indicator.' . $indicatorIndex . '.title']       = 'unique_lang|unique_default_lang';
            $rules['indicator.' . $indicatorIndex . '.description'] = 'unique_lang|unique_default_lang';

            if($this->version !== 'V203') {
                $rules['indicator.' . $indicatorIndex . '.baseline']    = 'size:1';
            }

            foreach (getVal($indicators, ['reference'], []) as $referenceIndex => $reference) {
                $rules['indicator.' . $indicatorIndex . '.reference.' . $referenceIndex . '.vocabulary'] = sprintf(
                    'required_with:%s|in:%s',
                    'indicator.' . $indicatorIndex . '.reference.' . $referenceIndex . '.code',
                    $this->indicatorVocabularyCodeList()
                );
                $rules['indicator.' . $indicatorIndex . '.reference.' . $referenceIndex . '.code']       = sprintf(
                    'required_with:%s',
                    'indicator.' . $indicatorIndex . '.reference.' . $referenceIndex . '.vocabulary'
                );
                
                $vocabindex = $this->data['indicator'][$indicatorIndex]['reference'][$referenceIndex]['vocabulary'] ;
                
                if($vocabindex == 99){
                    $rules['indicator.' . $indicatorIndex . '.reference.'. $referenceIndex . '.indicator_uri' ] =sprintf(
                        'url|required_with:%s', 'indicator.' . $indicatorIndex . '.reference.'. $referenceIndex . '.vocabulary'
                    );
                }
            }
            foreach ($indicators['baseline'] as $baselineIndex => $baselines) {
                $rules['indicator.' . $indicatorIndex . '.baseline.' . $baselineIndex . '.year']    = 'integer';
                $rules['indicator.' . $indicatorIndex . '.baseline.' . $baselineIndex . '.comment'] = 'unique_lang|unique_default_lang';
            }

            if (!empty($indicators['period'])) {
                foreach ($indicators['period'] as $periodIndex => $periods) {
                    $rules['indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.period_end.0.date']   = sprintf(
                        'required|date_format:Y-m-d|after:%s',
                        'indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.period_start.0.date'
                    );
                    $rules['indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.period_start.0.date'] = 'required|date_format:Y-m-d';
                    $rules['indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.target.0.comment'] = 'unique_lang|unique_default_lang';
                    $rules['indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.actual.0.comment'] = 'unique_lang|unique_default_lang';
                }
            }
        }

        $rules['type']                                                         = sprintf('required|in:%s', $this->resultTypeCodeList());
        $rules['aggregation_status']                                           = 'boolean';
        $rules['title.*.narrative.0.narrative']                                = 'required';
        $rules['title.*.narrative.0.language']                                 = sprintf('in:%s', $this->languageCodeList());
        $rules['title']                                                        = 'unique_lang|unique_default_lang';
        $rules['description.*.narrative.0.language']                           = sprintf('in:%s', $this->languageCodeList());
        $rules['description']                                                  = 'unique_lang|unique_default_lang';
        $rules['indicator.*.measure']                                          = sprintf('required|in:%s', $this->indicatorMeasureCodeList());
        $rules['indicator.*.ascending']                                        = 'boolean';
        $rules['indicator.*.title.*.narrative.0.narrative']                    = 'required';
        $rules['indicator.*.title.*.narrative.0.language']                     = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.description.*.narrative.0.language']               = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.reference.*.indicator_uri']                        = 'url';
        $rules['indicator.*.baseline.0.comment.*.narrative.0.language']        = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.period.*.target.0.comment.*.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.period.*.actual.0.comment.*.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['type.required']                                                   = trans('validation.required', ['attribute' => trans('elementForm.result_type')]);
        $messages['type.in']                                                         = trans('validation.code_list', ['attribute' => trans('elementForm.result_type')]);
        $messages['aggregation_status.boolean']                                      = trans('validation.boolean', ['attribute' => trans('elementForm.aggregation_status')]);
        $messages['title.*.narrative.0.narrative.required']                          = trans('validation.required', ['attribute' => trans('elementForm.title')]);
        $messages['title.*.narrative.0.language.in']                                 = trans('validation.invalid_language', ['attribute' => trans('elementForm.title')]);
        $messages['title.unique_lang']                                               = trans('validation.unique_lang', ['attribute' => trans('elementForm.title')]);
        $messages['description.unique_lang']                                         = trans('validation.unique_lang', ['attribute' => trans('elementForm.description')]);
        $messages['indicator.*.title.unique_lang']                                   = trans('validation.unique_lang', ['attribute' => trans('elementForm.indicator_title')]);
        $messages['indicator.*.description.unique_lang']                             = trans('validation.unique_lang', ['attribute' => trans('elementForm.indicator_description')]);
        $messages['indicator.*.baseline.0.comment.unique_lang']                      = trans('validation.unique_lang', ['attribute' => trans('elementForm.baseline_comment')]);
        $messages['indicator.*.period.*.target.0.comment.unique_lang']               = trans('validation.unique_lang', ['attribute' => trans('elementForm.period_target_comment')]);
        $messages['indicator.*.period.*.actual.0.comment.unique_lang']               = trans('validation.unique_lang', ['attribute' => trans('elementForm.period_actual_comment')]);
        $messages['description.*.narrative.0.language.in']                           = trans('validation.invalid_language', ['attribute' => trans('elementForm.description')]);
        $messages['indicator.*.measure.required']                                    = trans('validation.required', ['attribute' => trans('elementForm.indicator_measure')]);
        $messages['indicator.*.measure.in']                                          = trans('validation.code_list', ['attribute' => trans('elementForm.indicator_measure')]);
        $messages['indicator.*.ascending.boolean']                                   = trans('validation.indicator_ascending');
        $messages['indicator.*.title.*.narrative.0.narrative.required']              = trans('validation.required', ['attribute' => trans('elementForm.indicator_title')]);
        $messages['indicator.*.title.*.narrative.0.language.in']                     = trans('validation.invalid_language', ['attribute' => trans('elementForm.indicator_title')]);
        $messages['indicator.*.description.*.narrative.0.language.in']               = trans('validation.invalid_language', ['attribute' => trans('elementForm.indicator_description')]);
        $messages['indicator.*.reference.*.vocabulary.in']                           = trans('validation.code_list', ['attribute' => trans('elementForm.indicator_reference_vocabulary')]);
        $messages['indicator.*.reference.*.vocabulary.required_with']                = trans(
            'validation.required_with',
            ['attribute' => trans('elementForm.indicator_reference_vocabulary'), 'values' => trans('elementForm.indicator_reference_code')]
        );
        $messages['indicator.*.reference.*.code.required_with']                      = trans(
            'validation.required_with',
            ['attribute' => trans('elementForm.indicator_reference_code'), 'values' => trans('elementForm.indicator_reference_vocabulary')]
        );
        $messages['indicator.*.reference.*.indicator_uri.url']                       = trans('validation.code_list', ['attribute' => trans('elementForm.reference_url')]);
        $messages['indicator.*.baseline.0.year.integer']                             = trans('validation.integer', ['attribute' => trans('elementForm.indicator_baseline_year')]);
        $messages['indicator.*.baseline.size']                                       = trans('validation.indicator_size');
        $messages['indicator.*.baseline.0.comment.*.narrative.0.narrative.required'] = trans('validation.narrative_required', ['attribute' => trans('elementForm.baseline_comment')]);
        $messages['indicator.*.baseline.0.comment.*.narrative.0.language.in']        = trans('validation.invalid_language', ['attribute' => trans('elementForm.baseline_comment')]);
        $messages['indicator.*.period.*.target.size']                                = trans(
            'validation.no_more_than_once',
            ['attribute' => trans('elementForm.period_target_value'), 'values' => trans('elementForm.period')]
        );
        $messages['indicator.*.period.*.actual.size']                                = trans(
            'validation.no_more_than_once',
            ['attribute' => trans('elementForm.period_actual_value'), 'values' => trans('elementForm.period')]
        );
        $messages['indicator.*.period.*.period_start.0.date.required']                 = trans('validation.required', ['attribute' => trans('elementForm.period_start_date')]);
        $messages['indicator.*.period.*.period_start.0.date.date_format']            = trans('validation.csv_date', ['attribute' => trans('elementForm.period_start_date')]);
        $messages['indicator.*.period.*.period_end.0.date.required']                 = trans('validation.required', ['attribute' => trans('elementForm.period_end_date')]);
        $messages['indicator.*.period.*.period_end.0.date.date_format']              = trans('validation.csv_date', ['attribute' => trans('elementForm.period_start_date')]);
        $messages['indicator.*.period.*.period_end.0.date.after']                    = trans(
            'validation.after',
            ['attribute' => trans('elementForm.period_end_date'), 'date' => trans('elementForm.period_start_date')]
        );
        $messages['indicator.*.period.*.target.0.comment.*.narrative.0.language.in'] = trans('validation.invalid_language', ['attribute' => trans('elementForm.period_target_comment')]);
        $messages['indicator.*.period.*.actual.0.comment.*.narrative.0.language.in'] = trans('validation.invalid_language', ['attribute' => trans('elementForm.period_actual_comment')]);
        $messages['indicator.*.period.*.actual.0.value.required_with']               = trans(
            'validation.required_with',
            [
                'attribute' => trans('elementForm.period_actual_value'),
                'values'    => trans('elementForm.period_actual_comment')
                    . ', ' . trans('elementForm.period_actual_location_ref')
                    . ', ' . trans('elementForm.period_actual_dimension_value')
                    . ', ' . trans('elementForm.period_actual_dimension_name')
            ]
        );
        $messages['indicator.*.period.*.target.0.value.required_with']               = trans(
            'validation.required_with',
            [
                'attribute' => trans('elementForm.period_target_value'),
                'values'    => trans('elementForm.period_target_comment')
                    . ', ' . trans('elementForm.period_target_location_ref')
                    . ', ' . trans('elementForm.period_target_dimension_value')
                    . ', ' . trans('elementForm.period_target_dimension_name')
            ]
        );
        $messages['indicator.*.reference.*.indicator_uri.required_with']        = trans(
            'validation.required_with',
            [
                'attribute' => trans('elementForm.indicator_uri'),
                'values'    => trans('elementForm.reference_indicator_uri_if')
            ]
        );
        $messages['reference.*.indicator_uri.required_with']        = trans(
            'validation.required_with',
            [
                'attribute' => trans('elementForm.indicator_uri'),
                'values'    => trans('elementForm.reference_indicator_uri_if')
            ]
        );

        return $messages;
    }

    /**
     * Set the validity for the whole ActivityRow.
     *
     * @return $this
     */
    protected function validateSelf()
    {
        if (in_array(false, $this->validElements)) {
            $this->isValid = false;
        } else {
            $this->isValid = true;
        }

        return $this;
    }

    /**
     * Get the ResultType CodeList.
     *
     * @return string ResultType Code list
     */
    protected function resultTypeCodeList()
    {
        $resultTypes = $this->loadCodeList('ResultType', 'V201');
        $codes       = [];
        foreach ($resultTypes['ResultType'] as $type) {
            $codes[] = $type['code'];
        }

        return implode(',', $codes);
    }

    /**
     * Get the Language CodeList.
     *
     * @return string Language Code list
     */
    private function languageCodeList()
    {
        $languageList = $this->loadCodeList('Language', 'V201');

        $codes = [];
        foreach ($languageList['Language'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     * Loads Code list
     * @param        $codeList
     * @param        $version
     * @param string $directory
     * @return mixed
     */
    protected function loadCodeList($codeList, $version, $directory = "Activity")
    {
        return json_decode(file_get_contents(app_path(sprintf('Core/%s/Codelist/en/%s/%s.json', $version, $directory, $codeList))), true);
    }

    /**
     * Make the storage directory, if it does not exist, to store the validated Csv data before import.
     */
    protected function makeDirectoryIfNonExistent()
    {
        $path = sprintf('%s/%s/%s/', storage_path(self::CSV_DATA_STORAGE_PATH), $this->organizationId, $this->userId);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        shell_exec(sprintf('chmod 777 -R %s', $path));

        return $this;
    }

    /**
     * Get the file path for the validated Csv data to be stored before import.
     *
     * @return string
     */
    protected function getCsvFilepath()
    {
        if ($this->isValid) {
            return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, $this->organizationId, $this->userId, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, $this->organizationId, $this->userId, self::INVALID_CSV_FILE));
    }

    /**
     * Write the validated data into the designated destination file.
     *
     * @param $destinationFilePath
     */
    protected function writeCsvDataAsJson($destinationFilePath)
    {
        if (file_exists($destinationFilePath)) {
            $this->appendDataIntoFile($destinationFilePath);
        } else {
            $this->createNewFile($destinationFilePath);
        }
    }

    /**
     * Append data into the file containing previous data.
     *
     * @param $destinationFilePath
     */
    protected function appendDataIntoFile($destinationFilePath)
    {
        if ($currentContents = json_decode(file_get_contents($destinationFilePath), true)) {
            $currentContents[] = ['data' => $this->data(), 'errors' => $this->errors(), 'status' => 'processed'];

            file_put_contents($destinationFilePath, json_encode($currentContents));
        } else {
            $this->createNewFile($destinationFilePath);
        }
    }

    /**
     * Write the validated data into a new file.
     *
     * @param $destinationFilePath
     */
    protected function createNewFile($destinationFilePath)
    {
        file_put_contents($destinationFilePath, json_encode([['data' => $this->data(), 'errors' => $this->errors(), 'status' => 'processed']]));
        shell_exec(sprintf('chmod 777 -R %s', $destinationFilePath));
    }

    /**
     * Get all the errors associated with the current ActivityRow.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Record errors within the ActivityRow.
     */
    protected function recordErrors()
    {
        foreach ($this->validator->errors()->getMessages() as $errors) {
            foreach ($errors as $error) {
                $this->errors[] = $error;
            }
        }

        $this->errors = array_unique($this->errors);

        return $this;
    }

    /**
     * Get the Indicator Measure CodeList.
     *
     * @return string Indicator Measure Code list
     */
    protected function indicatorMeasureCodeList()
    {
        $list = $this->loadCodeList('IndicatorMeasure', 'V201');

        $codes = [];
        foreach ($list['IndicatorMeasure'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     * Get the Indicator Vocabulary CodeList.
     *
     * @return string Indicator VocabularyCodeList
     */
    protected function indicatorVocabularyCodeList()
    {
        $list = $this->loadCodeList('IndicatorVocabulary', 'V201');

        $codes = [];
        foreach ($list['IndicatorVocabulary'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     * Sets isValid attribute if validator passes or fails
     */
    protected function setValidity()
    {
        $this->isValid = $this->validator->passes();
    }

    /**
     * Check if a value is boolean and return its corresponding boolean integer value, i.e., 0 or 1.
     *
     * @param $values
     * @return bool
     */
    protected function isBoolean($values)
    {
        $values = strtolower($values);

        if (((int) $values === 1) || ($values === true) || ($values === "true") || ($values === "yes")) {
            return 1;
        }

        if ((preg_match('/^0$/', $values)) || ($values === false) || ($values === "false") || ($values === "no")) {
            return 0;
        }

        return $values;
    }

    /**
     * Initialize the Row object.
     * @return mixed
     */
    public function init()
    {
        //
    }
}

