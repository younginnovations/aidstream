<?php namespace App\Core\V201\Requests\Organization;

/**
 * Class Validation
 * common validation rules and messages
 * @package App\Core\V201\Requests\Organization
 */
class Validation
{
    /**
     * returns rules for narrative form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForNarrative($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[$formBase . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for narrative form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForNarrative($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[$formBase . '.narrative.' . $narrativeIndex . '.narrative.required'] = 'Narrative text is required';
        }

        return $messages;
    }

    /**
     * returns rules for value form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForValue($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $valueKey => $valueVal) {
            $valueForm                         = $formBase . '.value.' . $valueKey;
            $rules[$valueForm . '.amount']     = 'required|numeric';
            $rules[$valueForm . '.value_date'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for value form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForValue($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $valueKey => $valueVal) {
            $valueForm                                     = $formBase . '.value.' . $valueKey;
            $messages[$valueForm . '.amount.required']     = 'Amount is Required';
            $messages[$valueForm . '.amount.numeric']      = 'Amount should be numeric';
            $messages[$valueForm . '.value_date.required'] = 'Date is Required';
        }

        return $messages;
    }

    /**
     * returns rules for budget line form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForBudgetLine($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budgetLine.' . $budgetLineKey;
            $rules = array_merge(
                $rules,
                $this->addRulesForValue($budgetLineVal['value'], $budgetLineForm, $rules),
                $this->addRulesForNarrative($budgetLineVal['narrative'], $budgetLineForm, $rules)
            );
        }

        return $rules;
    }

    /**
     * returns messages for budget line form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesBudgetLine($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budgetLine.' . $budgetLineKey;
            $messages = array_merge(
                $messages,
                $this->addMessagesForValue($budgetLineVal['value'], $budgetLineForm, $messages),
                $this->addMessagesForNarrative($budgetLineVal['narrative'], $budgetLineForm, $messages)
            );
        }

        return $messages;
    }

    /**
     * returns rules for period start form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForPeriodStart($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $rules[$formBase . '.periodStart.' . $periodStartKey . '.date'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for period start form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForPeriodStart($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $messages[$formBase . '.periodStart.' . $periodStartKey . '.date.required'] = 'Period Start is required';
        }

        return $messages;
    }

    /**
     * returns rules for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForPeriodEnd($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $rules[$formBase . '.periodEnd.' . $periodEndKey . '.date'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForPeriodEnd($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $messages[$formBase . '.periodEnd.' . $periodEndKey . '.date.required'] = 'Period End is required';
        }

        return $messages;
    }
}
