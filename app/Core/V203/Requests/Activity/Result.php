<?php namespace App\Core\V203\Requests\Activity;

use App\Core\V202\Requests\Activity\Result as V202Result;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Validator;

/**
 * Class Result
 * @package App\Core\V202\Requests\Activity
 */
class Result extends V202Result
{
    function __construct()
    {
        parent::__construct();
        Validator::extendImplicit(
            'baseline_indicator_with_value_nonqualitative_validation',
            function ($attribute, $value, $parameters, $validator)
            {
                if($parameters[0] == null){
                    return true;
                }
                if ($parameters[0] != "5") {
                    if ($value == '' || !is_numeric($value)) {
                        return false;
                    }
                }

                return true;
            }
        );
        Validator::extendImplicit(
            'baseline_indicator_with_value_qualitative_validation',
            function ($attribute, $value, $parameters, $validator)
            {
                if($parameters[0] == null){
                    return true;
                }

                if ($parameters[0] == "5") {
                    if($value != ''){
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * returns rules for result
     * @param $formFields
     * @return array|mixed
     */
    protected function getRulesForResult($formFields)
    {
        $rules = [];

        foreach ($formFields as $resultIndex => $result) {
            $resultForm                             = sprintf('result.%s', $resultIndex);
            $rules[sprintf('%s.type', $resultForm)] = 'required';
            $rules                                  = array_merge(
                $rules,
                $this->getRulesForRequiredNarrative($result['title'][0]['narrative'], sprintf('%s.title.0', $resultForm)),
                $this->getRulesForNarrative($result['description'][0]['narrative'], sprintf('%s.description.0', $resultForm)),
                $this->getRulesForIndicator($result['indicator'], $resultForm),
                $this->getRulesForReferences($result['reference'], $resultForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for result
     * @param $formFields
     * @return array|mixed
     */
    protected function getMessagesForResult($formFields)
    {
        $messages = [];

        foreach ($formFields as $resultIndex => $result) {
            $resultForm                                         = sprintf('result.%s', $resultIndex);
            $messages[sprintf('%s.type.required', $resultForm)] = trans('validation.required', ['attribute' => trans('elementForm.type')]);
            $messages                                           = array_merge(
                $messages,
                $this->getMessagesForRequiredNarrative($result['title'][0]['narrative'], sprintf('%s.title.0', $resultForm)),
                $this->getMessagesForNarrative($result['description'][0]['narrative'], sprintf('%s.description.0', $resultForm)),
                $this->getMessagesForIndicator($result['indicator'], $resultForm),
                $this->getMessagesForReferences($result['reference'], $resultForm)
            );
        }

        return $messages;
    }
    /**
     * returns rules for Reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesforReferences($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm = sprintf('%s.reference.%s', $formBase, $referenceIndex);
            $rules[sprintf('%s.vocabulary', $referenceForm)]        = sprintf('required_with:%s,%s', $referenceForm . '.code', $referenceForm. '.vocabulary_uri');
            $rules[sprintf('%s.code', $referenceForm)]              = 'required_with:' . $referenceForm . '.vocabulary';
            $rules[sprintf('%s.vocabulary_uri', $referenceForm)]    = 'url|required_with:' . $referenceForm . '.vocabulary';
        }

        return $rules;
    }

     /**
     * returns messages for Reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForReferences($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $referenceIndex => $reference) {

            $referenceForm = sprintf('%s.reference.%s', $formBase, $referenceIndex);
            $messages[sprintf('%s.vocabulary.required_with', $referenceForm)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.vocabulary'),
                    'values'    => trans('elementForm.vocab_or_code')
                ]
            );
            $messages[sprintf('%s.code.required_with', $referenceForm)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.code'),
                    'values'    => trans('elementForm.vocabulary')
                ]
            );
            $messages[sprintf('%s.vocabulary_uri.url', $referenceForm)] = trans('validation.url');

            $messages[sprintf('%s.vocabulary_uri.%s', $referenceForm, 'required_with')] = trans(
                    'validation.required_with',
                    [
                        'attribute' => trans('elementForm.vocabulary_uri'),
                        'values'    => trans('elementForm.reference_vocabulary')
                    ]
                );
        }

        return $messages;
    }

    /**
     * returns rules for indicator
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForIndicator($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $indicatorIndex => $indicator) {
            $indicatorForm                                = sprintf('%s.indicator.%s', $formBase, $indicatorIndex);
            $rules[sprintf('%s.measure', $indicatorForm)] = 'required';
            $rules                                        = array_merge(
                $rules,
                $this->getRulesForResultNarrative($indicator['title'], sprintf('%s.title.0', $indicatorForm)),
                $this->getRulesForNarrative($indicator['description'], sprintf('%s.description.0', $indicatorForm)),
                $this->getRulesForReference($indicator['reference'], $indicatorForm),
                $this->getRulesForBaseline($indicator['baseline'], $indicatorForm, $indicator['measure']),
                $this->getRulesForPeriod($indicator['period'], $indicatorForm, $indicator['measure'])
            );
        }

        return $rules;
    }

    /**
     * returns rules for reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForReference($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                      = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );
            $rules[sprintf('%s.vocabulary', $referenceForm)]    = sprintf('required_with:%s,%s', $referenceForm . '.code', $referenceForm. '.indicator_uri');
            $rules[sprintf('%s.code', $referenceForm)]          = 'required_with:' . $referenceForm . '.vocabulary';
            $rules[sprintf('%s.indicator_uri', $referenceForm)] = 'url';

            if ($reference['vocabulary'] == "99") {
                $rules[sprintf('%s.indicator_uri', $referenceForm)] = 'url|required_with:' . $referenceForm . '.vocabulary';
            }
        }

        return $rules;
    }

    /**
     * returns rules for baseline
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForBaseline($formFields, $formBase, $measure)
    {
        $rules = [];

        foreach ($formFields as $baselineIndex => $baseline) {
            $baselineForm                                   = sprintf('%s.baseline.%s', $formBase, $baselineIndex);

            $rules[sprintf('%s.year', $baselineForm)][]     = 'required_with:' . $baselineForm . '.date';

            $rules[sprintf('%s.year', $baselineForm)][]     = 'required_with:' . $baselineForm . '.value';

            foreach ($baseline['ref'] as $key => $value) {
                $rules[sprintf('%s.year', $baselineForm)][] = 'required_with:' .$baselineForm. '.location.' . $key . '.ref';
            }

            foreach ($baseline['dimension'] as $key => $value) {
                $rules[sprintf('%s.year', $baselineForm)][] = 'required_with:' . $baselineForm . '.dimension.' . $key . '.name';
                $rules[sprintf('%s.year', $baselineForm)][] = 'required_with:' . $baselineForm . '.dimension.' . $key . '.value';
            }

            foreach ($baseline['comment'] as $narrativeIndex => $narrative) {
                foreach ($narrative as $key => $value) {
                    foreach ($value as $k => $v) {
                        $rules[sprintf('%s.year', $baselineForm)][] = 'required_with:' . $baselineForm . '.comment.' . $narrativeIndex . '.'. $key .'.'. $k. '.narrative';
                        $rules[sprintf('%s.year', $baselineForm)][] = 'required_with:' . $baselineForm . '.comment.' . $narrativeIndex . '.'. $key . '.' . $k. '.language';
                    }
                }
            }

            $rules[sprintf('%s.year', $baselineForm)][]     = 'date_format:Y';
            $rules[sprintf('%s.year', $baselineForm)][]     = 'digits:4';

            $rules[sprintf('%s.value', $baselineForm)][]    = 'baseline_indicator_with_value_nonqualitative_validation:'.$measure;
            $rules[sprintf('%s.value', $baselineForm)][]    = 'baseline_indicator_with_value_qualitative_validation:'. $measure;

            $rules                                          = array_merge(
                $rules,
                $this->getRulesForNarrative($baseline['comment'][0]['narrative'], sprintf('%s.comment.0', $baselineForm))
            );
        }

        return $rules;
    }

    /**
     * returns messages for baseline
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForBaseline($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $baselineIndex => $baseline) {
            $baselineForm                                                           = sprintf('%s.baseline.%s', $formBase, $baselineIndex);

            $messages[sprintf('%s.year.required_with', $baselineForm)] = trans(
                'validation.required_with_all',
                [
                    'attribute' => trans('elementForm.year'),
                    'values'    => ''. trans('elementForm.date') . ', '
                                     . trans('elementForm.value') . ', '
                                     . trans('elementForm.location') . ', '
                                     . trans('elementForm.dimension'). ' or '
                                     . trans('elementForm.comment')
                ]
            );
            $messages[sprintf('%s.value.baseline_indicator_with_value_nonqualitative_validation', $baselineForm)] = trans('elementForm.value_if_nonqualitative');
            $messages[sprintf('%s.value.baseline_indicator_with_value_qualitative_validation', $baselineForm)] = trans('elementForm.value_if_qualitative');
            $messages[sprintf('%s.year.date_format', $baselineForm)]                = trans('validation.enter_valid',['attribute' => trans('elementForm.year')]);
            $messages[sprintf('%s.year.digits', $baselineForm)]                = trans('validation.digits',['attribute' => trans('elementForm.year')]);
            $messages                                                               = array_merge(
                $messages,
                $this->getMessagesForNarrative($baseline['comment'][0]['narrative'], sprintf('%s.comment.0', $baselineForm))
            );
        }

        return $messages;
    }

    /**
     * returns rules for period
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForPeriod($formFields, $formBase, $measure)
    {
        $rules = [];

        foreach ($formFields as $periodIndex => $period) {
            $periodForm = sprintf('%s.period.%s', $formBase, $periodIndex);
            $rules      = array_merge(
                $rules,
                $this->getRulesForResultPeriodStart($period['period_start'], $periodForm, $period['period_end']),
                $this->getRulesForResultPeriodEnd($period['period_end'], $periodForm, $period['period_start']),
                $this->getRulesForTarget($period['target'], sprintf('%s.target', $periodForm), $measure),
                $this->getRulesForTarget($period['actual'], sprintf('%s.actual', $periodForm), $measure)
            );
        }

        return $rules;
    }

     /**
     * returns rules for Target
     * @param $formFields
     * @return array|mixed
     */

    protected function getRulesForTarget($formFields, $formBase, $measure)
    {
        $rules = [];
        foreach ($formFields as $targetIndex => $target) {
            $targetForm         = sprintf('%s.%s', $formBase, $targetIndex);
            $rules[sprintf('%s.value', $targetForm)][] = 'baseline_indicator_with_value_nonqualitative_validation:'. $measure;
            $rules[sprintf('%s.value', $targetForm)][] = 'baseline_indicator_with_value_qualitative_validation:'. $measure;
            $rules = array_merge(
                $rules,
                $this->getRulesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $rules;
    }

    /**
     * returns messages for period
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForPeriod($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $periodIndex => $period) {
            $periodForm = sprintf('%s.period.%s', $formBase, $periodIndex);
            $messages   = array_merge(
                $messages,
                $this->getMessagesForResultPeriodStart($period['period_start'], $periodForm, $period['period_end']),
                $this->getMessagesForResultPeriodEnd($period['period_end'], $periodForm, $period['period_start']),
                $this->getMessagesForTarget($period['target'], sprintf('%s.target', $periodForm)),
                $this->getMessagesForTarget($period['actual'], sprintf('%s.actual', $periodForm))
            );
        }

        return $messages;
    }

    /**
     * returns messages for target
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForTarget($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $targetIndex => $target) {
            $targetForm                                                          = sprintf('%s.%s', $formBase, $targetIndex);
            $messages[sprintf('%s.value.baseline_indicator_with_value_nonqualitative_validation', $targetForm)] = trans('elementForm.value_if_nonqualitative');
            $messages[sprintf('%s.value.baseline_indicator_with_value_qualitative_validation', $targetForm)] = trans('elementForm.value_if_qualitative');
            $messages = array_merge(
                $messages,
                $this->getMessagesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $messages;
    }

    /**
     * returns messages for indicator
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForIndicator($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $indicatorIndex => $indicator) {
            $indicatorForm                                            = sprintf(
                '%s.indicator.%s',
                $formBase,
                $indicatorIndex
            );
            $messages[sprintf('%s.measure.required', $indicatorForm)] = trans('validation.required', ['attribute' => trans('elementForm.measure')]);
            $messages                                                 = array_merge(
                $messages,
                $this->getMessagesForNarrative($indicator['title'], sprintf('%s.title.0', $indicatorForm)),
                $this->getMessagesForResultNarrative($indicator['title'], sprintf('%s.title.0', $indicatorForm)),
                $this->getMessagesForNarrative($indicator['description'], sprintf('%s.description.0', $indicatorForm)),
                $this->getMessagesForReference($indicator['reference'], $indicatorForm),
                $this->getMessagesForBaseline($indicator['baseline'], $indicatorForm),
                $this->getMessagesForPeriod($indicator['period'], $indicatorForm)
            );
        }

        return $messages;
    }

    /**
     * returns the message for indicator title.
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForResultNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = trans('validation.unique', ['attribute' => trans('elementForm.language')]);
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf(
                '%s.narrative.%s.narrative.required',
                $formBase,
                $narrativeIndex
            )] = trans('validation.required', ['attribute' => trans('elementForm.indicator_narrative')]);
        }

        return $messages;

    }

    /**
     * returns messages for reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForReference($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                             = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );
            $messages[sprintf('%s.vocabulary.required_with', $referenceForm)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.vocabulary'),
                    'values'    => 'Indicator Uri or Code'
                ]
            );
            $messages[sprintf('%s.code.required_with', $referenceForm)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.code'),
                    'values'    => trans('elementForm.vocabulary')
                ]
            );
            $messages[sprintf('%s.indicator_uri.url', $referenceForm)] = trans('validation.url');

            if ( $reference['vocabulary'] == "99" ) {
                $messages[sprintf('%s.indicator_uri.%s', $referenceForm, 'required_with')] = trans(
                    'validation.required_with',
                    [
                        'attribute' => trans('elementForm.indicator_uri'),
                        'values'    => trans('elementForm.reference_indicator_uri_if')
                    ]
                );
            }
        }

        return $messages;
    }
}
