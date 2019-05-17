<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\Result as V201Result;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Validator;

/**
 * Class Result
 * @package App\Core\V202\Requests\Activity
 */
class Result extends V201Result
{
    function __construct()
    {
        parent::__construct();

        Validator::extendImplicit(
            'unique_vocabulary',
            function($attribute, $value, $parameters, $validator){
                foreach ($value as $key => $reference) {
                    $vocabulary[] = $reference['vocabulary'];
                }
                $original_length = count($vocabulary);
                $new_length = count(array_unique($vocabulary));

                if($original_length !== $new_length){
                    return false;
                }

                return true;
            }
        );
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
                $this->getRulesForBaseline($indicator['baseline'], $indicatorForm),
                $this->getRulesForPeriod($indicator['period'], $indicatorForm)
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

        if (count($formFields) > 1) {
            $referenceForm          = sprintf('%s.reference', $formBase);
            $rules[$referenceForm]  = 'unique_vocabulary:' . $referenceForm;
        }

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm  = sprintf('%s.reference.%s', $formBase, $referenceIndex);

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
     * returns rules for period
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForPeriod($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $periodIndex    => $period) {
            $periodForm                         = sprintf('%s.period.%s', $formBase, $periodIndex);
            $rules                              = array_merge(
                $rules,
                $this->getRulesForResultPeriodStart($period['period_start'], $periodForm, $period['period_end']),
                $this->getRulesForResultPeriodEnd($period['period_end'], $periodForm, $period['period_start']),
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

        foreach ($formFields as $periodIndex    => $period) {
            $periodForm                         = sprintf('%s.period.%s', $formBase, $periodIndex);
            $messages                           = array_merge(
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

        if (count($formFields > 1)) {
            $referenceForm = sprintf('%s.reference', $formBase);
            $messages[sprintf('%s.unique_vocabulary', $referenceForm)]        = trans('elementForm.unique_vocabulary');
        }

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                                    = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );

            $messages[sprintf('%s.vocabulary.required_with', $referenceForm)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.vocabulary'),
                    'values'    => trans('elementForm.indicator_or_code')
                ]
            );
            $messages[sprintf('%s.code.required_with', $referenceForm)]       = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.code'),
                    'values'    => trans('elementForm.vocabulary')
                ]
            );

            $messages[sprintf('%s.indicator_uri.url', $referenceForm)]        = trans('validation.url');

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
