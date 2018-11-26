<?php namespace App\Core\V203\Requests\Organization;

use App\Core\V201\Requests\Organization\OrganizationBaseRequest;

/**
 * Class TotalExpenditure
 * @package App\Core\V202\Requests\Organization
 */
class TotalExpenditure extends OrganizationBaseRequest
{
    /**
     * rules for total expenditure
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForTotalExpenditure($this->get('total_expenditure'));
    }

    /**
     * prepare messages for total expenditure
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForTotalExpenditure($this->get('total_expenditure'));
    }

    /**
     *  rules for organization total expenditure
     * @param $formFields
     * @return array
     */
    public function getRulesForTotalExpenditure($formFields)
    {
        $rules = [];
        foreach ($formFields as $totalExpenditureIndex => $totalExpenditure) {
            $totalExpenditureForm = sprintf('total_expenditure.%s', $totalExpenditureIndex);
            $rules                = array_merge(
                $rules,
                $this->getRulesForPeriodStart($totalExpenditure['period_start'], $totalExpenditureForm),
                $this->getRulesForPeriodEnd($totalExpenditure['period_end'], $totalExpenditureForm),
                $this->getRulesForValue($totalExpenditure['value'], $totalExpenditureForm),
                $this->getRulesForExpenseLine($totalExpenditure['expense_line'], $totalExpenditureForm)
            );
        }

        return $rules;
    }

    /**
     *  messages for organization total expenditure
     * @param $formFields
     * @return array
     */
    public function getMessagesForTotalExpenditure($formFields)
    {
        $messages = [];
        foreach ($formFields as $totalExpenditureIndex => $totalExpenditure) {
            $totalExpenditureForm = sprintf('total_expenditure.%s', $totalExpenditureIndex);
            $messages             = array_merge(
                $messages,
                $this->getMessagesForPeriodStart($totalExpenditure['period_start'], $totalExpenditureForm),
                $this->getMessagesForPeriodEnd($totalExpenditure['period_end'], $totalExpenditureForm),
                $this->getMessagesForValue($totalExpenditure['value'], $totalExpenditureForm),
                $this->getMessagesForExpenseLine($totalExpenditure['expense_line'], $totalExpenditureForm)
            );
        }

        return $messages;
    }

    /**
     *  rules for expense line
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForExpenseLine($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $expenseLineIndex => $expenseLine) {
            $expenseLineForm = sprintf('%s.expense_line.%s', $formBase, $expenseLineIndex);
            $rules           = array_merge(
                $rules,
                $this->getRulesForBudgetOrExpenseLineValue($expenseLine['value'], $expenseLineForm),
                $this->getRulesForBudgetOrExpenseLineNarrative($expenseLine['narrative'], $expenseLineForm, $expenseLineIndex)
            );
        }

        return $rules;
    }

    /**
     * messages for expense line
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForExpenseLine($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $expenseLineIndex => $expenseLine) {
            $expenseLineForm                                                                          = sprintf('%s.expense_line.%s', $formBase, $expenseLineIndex);
            $messages[sprintf('%s.expense_line.%s.reference.required', $formBase, $expenseLineIndex)] = trans('validation.required', ['attribute' => trans('elementForm.reference')]);
            $messages                                                                                 = array_merge(
                $messages,
                $this->getMessagesForBudgetOrExpenseLineValue($expenseLine['value'], $expenseLineForm, "Expense Line"),
                $this->getMessagesForBudgetOrExpenseLineNarrative($expenseLine['narrative'], $expenseLineForm, "Expense line")
            );
        }

        return $messages;
    }
}
