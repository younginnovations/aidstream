<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class Condition
 * @package App\Core\V201\Requests\Activity
 */
class Condition extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForCondition($this->request->get('condition'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForCondition($this->request->get('condition'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForCondition(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $conditionIndex => $condition) {
            $conditionForm                                       = sprintf('condition.%s', $conditionIndex);
            $rules['condition_attached']                         = 'required';
            $rules[sprintf('%s.condition_type', $conditionForm)] = 'required';
            $rules                                               = array_merge(
                $rules,
                $this->getRulesForNarrative($condition['narrative'], $conditionForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForCondition(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $conditionIndex => $condition) {
            $conditionForm                                                   = sprintf('condition.%s', $conditionIndex);
            $messages['condition_attached.required']                         = 'Condition Attached is Required';
            $messages[sprintf('%s.condition_type.required', $conditionForm)] = 'Condition Type is Required';
            $messages                                                        = array_merge(
                $messages,
                $this->getMessagesForNarrative($condition['narrative'], $conditionForm)
            );
        }

        return $messages;
    }
}
