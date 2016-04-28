<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\Budget as V201BudgetRequest;
use Carbon\Carbon;

/**
 * Class Budget
 * @package App\Core\V202\Requests\Activity
 */
class Budget extends V201BudgetRequest
{
    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForBudget(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $budgetIndex => $budget) {
            $budgetForm = sprintf('budget.%s', $budgetIndex);
            $newDate    = Carbon::createFromFormat('Y-m-d', $budget['period_start'][0]['date'])->addYear(1);

            $rules[sprintf('%s.status', $budgetForm)] = 'required';
            $rules                                    = array_merge(
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
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForBudget(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $budgetIndex => $budget) {
            $budgetForm = sprintf('budget.%s', $budgetIndex);

            $messages[sprintf('%s.status.required', $budgetForm)] = 'Status is required.';
            $messages                                             = array_merge(
                $messages,
                $this->getMessagesForPeriodStart($budget['period_start'], $budgetForm),
                $this->getMessagesForPeriodEnd($budget['period_end'], $budgetForm),
                $this->getMessagesForValue($budget['value'], $budgetForm)
            );
            $messages[$budgetForm.'.period_end.0.date.before']    = 'Period End must not be more than a year from Period Start';

        }

        return $messages;
    }
}
