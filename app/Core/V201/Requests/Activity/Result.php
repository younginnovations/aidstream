<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Support\Facades\Validator;

/**
 * Class Result
 * @package App\Core\V201\Requests\Activity
 */
class Result extends ActivityBaseRequest
{
    function __construct()
    {
        parent::__construct();
        Validator::extendImplicit(
            'year_value_narrative_validation',
            function ($attribute, $value, $parameters, $validator) {
                $narratives   = $value['comment'][0]['narrative'];
                $hasNarrative = false;
                foreach ($narratives as $narrative) {
                    if ($narrative['narrative']) {
                        $hasNarrative = true;
                        break;
                    }
                }

                if (!$hasNarrative) {
                    return true;
                }

                isset($value['year']) ?: $value['year'] = null;
                isset($value['value']) ?: $value['value'] = null;

                return ($hasNarrative && ($value['year'] || $value['value']));
            }
        );
    }

    /**
     * Get the validation rules that apply to the request.
     * prepare the error message
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForResult($this->get('result'));
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForResult($this->get('result'));
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
                $this->getRulesForIndicator($result['indicator'], $resultForm)
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
            $messages[sprintf('%s.type.required', $resultForm)] = 'Type is required.';
            $messages                                           = array_merge(
                $messages,
                $this->getMessagesForRequiredNarrative($result['title'][0]['narrative'], sprintf('%s.title.0', $resultForm)),
                $this->getMessagesForNarrative($result['description'][0]['narrative'], sprintf('%s.description.0', $resultForm)),
                $this->getMessagesForIndicator($result['indicator'], $resultForm)
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
                $this->getRulesForNarrative($indicator['title'][0]['narrative'], sprintf('%s.title.0', $indicatorForm)),
                $this->getRulesForNarrative($indicator['description'][0]['narrative'], sprintf('%s.description.0', $indicatorForm)),
                $this->getRulesForBaseline($indicator['baseline'], $indicatorForm),
                $this->getRulesForPeriod($indicator['period'], $indicatorForm)
            );
        }

        return $rules;
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
            $indicatorForm                                            = sprintf('%s.indicator.%s', $formBase, $indicatorIndex);
            $messages[sprintf('%s.measure.required', $indicatorForm)] = 'Measure is required.';
            $messages                                                 = array_merge(
                $messages,
                $this->getMessagesForNarrative($indicator['title'][0]['narrative'], sprintf('%s.title.0', $indicatorForm)),
                $this->getMessagesForNarrative($indicator['description'][0]['narrative'], sprintf('%s.description.0', $indicatorForm)),
                $this->getMessagesForBaseline($indicator['baseline'], $indicatorForm),
                $this->getMessagesForPeriod($indicator['period'], $indicatorForm)
            );
        }

        return $messages;
    }

    /**
     * returns rules for baseline
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForBaseline($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $baselineIndex => $baseline) {
            $baselineForm                              = sprintf('%s.baseline.%s', $formBase, $baselineIndex);
            $rules[$baselineForm]                      = 'year_value_narrative_validation:' . $baselineForm . '.comment.0.narrative';
            $rules[sprintf('%s.year', $baselineForm)]  = sprintf('numeric|required_with:%s.value', $baselineForm);
            $rules[sprintf('%s.value', $baselineForm)] = sprintf('numeric|required_with:%s.year', $baselineForm);
            $rules                                     = array_merge(
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
            $messages[sprintf('%s.year_value_narrative_validation', $baselineForm)] = 'Year and Value is required if narrative is not empty.';
            $messages[sprintf('%s.year.required_with', $baselineForm)]              = 'Year is required with value.';
            $messages[sprintf('%s.year.numeric', $baselineForm)]                    = 'Year should be numeric.';
            $messages[sprintf('%s.value.required_with', $baselineForm)]             = 'Value is required with year.';
            $messages[sprintf('%s.value.numeric', $baselineForm)]                   = 'Value should be numeric.';
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
    protected function getRulesForPeriod($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $periodIndex => $period) {
            $periodForm = sprintf('%s.period.%s', $formBase, $periodIndex);
            $rules      = array_merge(
                $rules,
                $this->getRulesForPeriodStart($period['period_start'], $periodForm),
                $this->getRulesForPeriodEnd($period['period_end'], $periodForm),
                $this->getRulesForTarget($period['target'], sprintf('%s.target', $periodForm)),
                $this->getRulesForTarget($period['actual'], sprintf('%s.actual', $periodForm))
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
                $this->getMessagesForPeriodStart($period['period_start'], $periodForm),
                $this->getMessagesForPeriodEnd($period['period_end'], $periodForm),
                $this->getMessagesForTarget($period['target'], sprintf('%s.target', $periodForm)),
                $this->getMessagesForTarget($period['actual'], sprintf('%s.actual', $periodForm))
            );
        }

        return $messages;
    }

    /**
     * returns rules for target
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForTarget($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $targetIndex => $target) {
            $targetForm         = sprintf('%s.%s', $formBase, $targetIndex);
            $rules[$targetForm] = 'year_value_narrative_validation';
            $rules              = array_merge(
                $rules,
                $this->getRulesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $rules;
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
            $targetForm                                                           = sprintf('%s.%s', $formBase, $targetIndex);
            $messages[sprintf('%s.year_value_narrative_validation', $targetForm)] = 'Value is required if narrative is not empty.';
            $messages                                                             = array_merge(
                $messages,
                $this->getMessagesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $messages;
    }
}
