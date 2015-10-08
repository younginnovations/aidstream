<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class Validation
 * common validation rules and messages
 * @package App\Core\V201\Requests\Activity
 */
class Validation
{
    /**
     * returns rules for narrative
     * @param $formFields
     * @param $formBase
     * @param $rules
     * @return array
     */
    public function addRulesForNarrative($formFields, $formBase, $rules)
    {
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[$formBase . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for narrative
     * @param $formFields
     * @param $formBase
     * @param $messages
     * @return array
     */
    public function addMessagesForNarrative($formFields, $formBase, $messages)
    {
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[$formBase . '.narrative.' . $narrativeIndex . '.narrative.required'] = 'Narrative text is required';
        }

        return $messages;
    }
}
