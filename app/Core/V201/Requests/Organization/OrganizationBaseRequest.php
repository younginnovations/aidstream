<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;

/**
 * Class OrganizationBaseRequest
 * common validation rules and messages
 * @package App\Core\V201\Requests\Organization
 */
class OrganizationBaseRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * returns rules for narrative form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForNarrative($formFields, $formBase)
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
    public function getMessagesForNarrative($formFields, $formBase)
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
    public function getRulesForValue($formFields, $formBase)
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
    public function getMessagesForValue($formFields, $formBase)
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
    public function getRulesForBudgetLine($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budget_line.' . $budgetLineKey;
            $rules          = array_merge(
                $rules,
                $this->getRulesForValue($budgetLineVal['value'], $budgetLineForm, $rules),
                $this->getRulesForNarrative($budgetLineVal['narrative'], $budgetLineForm, $rules)
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
    public function getMessagesBudgetLine($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budget_line.' . $budgetLineKey;
            $messages       = array_merge(
                $messages,
                $this->getMessagesForValue($budgetLineVal['value'], $budgetLineForm, $messages),
                $this->getMessagesForNarrative($budgetLineVal['narrative'], $budgetLineForm, $messages)
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
    public function getRulesForPeriodStart($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $rules[$formBase . '.period_start.' . $periodStartKey . '.date'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for period start form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodStart($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $messages[$formBase . '.period_start.' . $periodStartKey . '.date.required'] = 'Period Start is required';
        }

        return $messages;
    }

    /**
     * returns rules for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPeriodEnd($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodEnd($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.required'] = 'Period End is required';
        }

        return $messages;
    }
}
