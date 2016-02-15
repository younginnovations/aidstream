<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\Budget as V201BudgetRequest;

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

            $rules[sprintf('%s.status', $budgetForm)] = 'required';
            $rules                                    = array_merge(
                $rules,
                $this->getRulesForPeriodStart($budget['period_start'], $budgetForm),
                $this->getRulesForPeriodEnd($budget['period_end'], $budgetForm),
                $this->getRulesForValue($budget['value'], $budgetForm)
            );
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
        }

        return $messages;
    }
}
