<?php namespace App\Core\V201\Requests\Organization;

class CreateOrgRecipientOrgBudgetRequest extends OrganizationBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->get(
            'recipient_organization_budget'
        ) as $recipientOrganizationBudgetIndex => $recipientOrganizationBudget) {
            $recipientOrganizationBudgetForm = sprintf(
                'recipient_organization_budget.%s',
                $recipientOrganizationBudgetIndex
            );
            $rules                           = array_merge(
                $rules,
                $this->getRulesForPeriodStart(
                    $recipientOrganizationBudget['period_start'],
                    $recipientOrganizationBudgetForm
                ),
                $this->getRulesForPeriodEnd(
                    $recipientOrganizationBudget['period_end'],
                    $recipientOrganizationBudgetForm
                ),
                $this->getRulesForValue($recipientOrganizationBudget['value'], $recipientOrganizationBudgetForm),
                $this->getRulesForNarrative($recipientOrganizationBudget['narrative'], $recipientOrganizationBudgetForm)
            );
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->get(
            'recipient_organization_budget'
        ) as $recipientOrganizationBudgetIndex => $recipientOrganizationBudget) {
            $recipientOrganizationBudgetForm = sprintf(
                'recipient_organization_budget.%s',
                $recipientOrganizationBudgetIndex
            );
            $messages                        = array_merge(
                $messages,
                $this->getMessagesForPeriodStart(
                    $recipientOrganizationBudget['period_start'],
                    $recipientOrganizationBudgetForm
                ),
                $this->getMessagesForPeriodEnd(
                    $recipientOrganizationBudget['period_end'],
                    $recipientOrganizationBudgetForm
                ),
                $this->getMessagesForValue($recipientOrganizationBudget['value'], $recipientOrganizationBudgetForm),
                $this->getMessagesForNarrative(
                    $recipientOrganizationBudget['narrative'],
                    $recipientOrganizationBudgetForm
                )
            );
        }

        return $messages;
    }
}
