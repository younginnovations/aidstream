<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\Result as V201Result;
use Illuminate\Database\DatabaseManager;

/**
 * Class Result
 * @package App\Core\V202\Requests\Activity
 */
class Result extends V201Result
{
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

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                      = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );
            $rules[sprintf('%s.indicator_uri', $referenceForm)] = 'url';
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
            $indicatorForm                                            = sprintf(
                '%s.indicator.%s',
                $formBase,
                $indicatorIndex
            );
            $messages[sprintf('%s.measure.required', $indicatorForm)] = 'Measure is required.';
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
            $messages[sprintf('%s.indicator_uri.url', $referenceForm)] = trans('validation.url');
        }

        return $messages;
    }
}
