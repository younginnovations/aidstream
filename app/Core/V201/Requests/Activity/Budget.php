<?php namespace App\Core\V201\Requests\Activity;

use Carbon\Carbon;

/**
 * Class Budget
 * @package App\Core\V201\Requests\Activity
 */
class Budget extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForBudget($this->get('budget'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForBudget(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $budgetIndex => $budget) {
            $budgetForm = sprintf('budget.%s', $budgetIndex);

            $newDate = Carbon::createFromFormat('Y-m-d', $budget['period_start'][0]['date'])->addYear(1);

            $rules                                     = array_merge(
                $rules,
                $this->getRulesForPeriodStart($budget['period_start'], $budgetForm),
                $this->getRulesForPeriodEnd($budget['period_end'], $budgetForm),
                $this->getRulesForValue($budget['value'], $budgetForm)
            );
            $rules[$budgetForm.'.period_end.0.date'][] = sprintf('before:%s', $newDate);
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForValue($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                                   = sprintf('%s.value.%s', $formBase, $valueIndex);
            $rules[sprintf('%s.amount', $valueForm)]     = 'required|numeric';
            $rules[sprintf('%s.value_date', $valueForm)] = 'required';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForBudget($this->get('budget'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForBudget(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $budgetIndex => $budget) {
            $budgetForm = sprintf('budget.%s', $budgetIndex);

            $messages = array_merge(
                $messages,
                $this->getMessagesForPeriodStart($budget['period_start'], $budgetForm),
                $this->getMessagesForPeriodEnd($budget['period_end'], $budgetForm),
                $this->getMessagesForValue($budget['value'], $budgetForm)
            );

            $messages[$budgetForm.'.period_end.0.date.before'] = 'Period End must not be more than a year from Period Start';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForValue($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                                               = sprintf('%s.value.%s', $formBase, $valueIndex);
            $messages[sprintf('%s.amount.required', $valueForm)]     = 'Amount is required';
            $messages[sprintf('%s.amount.numeric', $valueForm)]      = 'Amount should be numeric';
            $messages[sprintf('%s.value_date.required', $valueForm)] = 'Date is required';
        }

        return $messages;
    }
}
