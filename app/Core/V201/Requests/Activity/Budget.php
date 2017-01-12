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
            $rules      = array_merge(
                $rules,
                $this->getRulesForPeriodStart($budget['period_start'], $budgetForm),
                $this->getRulesForPeriodEnd($budget['period_end'], $budgetForm),
                $this->getRulesForValue($budget['value'], $budgetForm)
            );

            $startDate = getVal($budget, ['period_start', 0, 'date']);
            $newDate   = $startDate ? date('Y-m-d', strtotime($startDate . '+1year')) : '';
            if ($newDate) {
                $rules[$budgetForm . '.period_end.0.date'][] = sprintf('before:%s', $newDate);
            }

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

            $messages[$budgetForm . '.period_end.0.date.before'] = trans('validation.before', ['attribute' => trans('elementForm.period_end'), 'date' => trans('elementForm.period_start')]);
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
            $messages[sprintf('%s.amount.required', $valueForm)]     = trans('validation.required', ['attribute' => trans('elementForm.amount')]);
            $messages[sprintf('%s.amount.numeric', $valueForm)]      = trans('validation.number', ['attribute' => trans('elementForm.amount')]);
            $messages[sprintf('%s.value_date.required', $valueForm)] = trans('validation.required', ['attribute' => trans('elementForm.date')]);
        }

        return $messages;
    }
}
