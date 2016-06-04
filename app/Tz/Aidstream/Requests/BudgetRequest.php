<?php namespace App\Tz\Aidstream\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BudgetRequest
 * @package App\Tz\Aidstream\Requests
 */
class BudgetRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForBudget($this->get('budget'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForBudget($this->get('budget'));
    }

    /**
     * @param $budgetDetails
     * @return array
     */
    protected function getRulesForBudget($budgetDetails)
    {
        $rules = [];

        foreach ($budgetDetails as $index => $detail) {
            $rules[sprintf('budget.%s.period_start.0.date', $index)] = 'required';
            $rules[sprintf('budget.%s.period_end.0.date', $index)]   = 'required';
            $rules[sprintf('budget.%s.value.0.amount', $index)]      = 'required|numeric';
            $rules[sprintf('budget.%s.value.0.currency', $index)]    = 'required';
        }

        return $rules;
    }

    /**
     * @param $budgetDetails
     * @return array
     */
    protected function getMessagesForBudget($budgetDetails)
    {
        $messages = [];

        foreach ($budgetDetails as $index => $detail) {
            $messages[sprintf('budget.%s.period_start.0.date.required', $index)] = 'The start date is required.';
            $messages[sprintf('budget.%s.period_end.0.date.required', $index)]   = 'The end date is required.';
            $messages[sprintf('budget.%s.value.0.amount.required', $index)]      = 'The Budget amount is required.';
            $messages[sprintf('budget.%s.value.0.amount.numeric', $index)]       = 'The Budget amount should be numeric.';
            $messages[sprintf('budget.%s.value.0.currency.required', $index)]    = 'The Currency is required.';
        }

        return $messages;
    }
}
