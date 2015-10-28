<?php namespace App\Core\V201\Requests\Organization;

class CreateTotalBudgetRequest extends OrganizationBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForTotalBudget($this->request->get('total_budget'));
    }

    public function messages()
    {
        return $this->addMessagesForTotalBudget($this->request->get('total_budget'));
    }

    /**
     * returns rules for total budget form
     * @param $formFields
     * @return array
     */
    public function addRulesForTotalBudget($formFields)
    {
        $rules = [];
        foreach ($formFields as $totalBudgetIndex => $totalBudget) {
            $totalBudgetForm = sprintf('total_budget.%s', $totalBudgetIndex);
            $rules           = array_merge(
                $rules,
                $this->addRulesForPeriodStart($totalBudget['period_start'], $totalBudgetForm),
                $this->addRulesForPeriodEnd($totalBudget['period_end'], $totalBudgetForm),
                $this->addRulesForValue($totalBudget['value'], $totalBudgetForm),
                $this->addRulesForBudgetLine($totalBudget['budget_line'], $totalBudgetForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for total budget form rules
     * @param $formFields
     * @return array
     */
    public function addMessagesForTotalBudget($formFields)
    {
        $messages = [];
        foreach ($formFields as $totalBudgetIndex => $totalBudget) {
            $totalBudgetForm = sprintf('total_budget.%s', $totalBudgetIndex);
            $messages        = array_merge(
                $messages,
                $this->addMessagesForPeriodStart($totalBudget['period_start'], $totalBudgetForm),
                $this->addMessagesForPeriodEnd($totalBudget['period_end'], $totalBudgetForm),
                $this->addMessagesForValue($totalBudget['value'], $totalBudgetForm),
                $this->addMessagesBudgetLine($totalBudget['budget_line'], $totalBudgetForm)
            );
        }

        return $messages;
    }
}
