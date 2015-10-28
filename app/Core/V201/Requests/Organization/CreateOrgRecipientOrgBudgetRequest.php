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
        foreach ($this->request->get(
            'recipient_organization_budget'
        ) as $recipientOrganizationBudgetIndex => $recipientOrganizationBudget) {
            $recipientOrganizationBudgetForm = sprintf(
                'recipient_organization_budget.%s',
                $recipientOrganizationBudgetIndex
            );
            $rules                           = array_merge(
                $rules,
                $this->addRulesForPeriodStart(
                    $recipientOrganizationBudget['period_start'],
                    $recipientOrganizationBudgetForm
                ),
                $this->addRulesForPeriodEnd(
                    $recipientOrganizationBudget['period_end'],
                    $recipientOrganizationBudgetForm
                ),
                $this->addRulesForValue($recipientOrganizationBudget['value'], $recipientOrganizationBudgetForm),
                $this->addRulesForNarrative($recipientOrganizationBudget['narrative'], $recipientOrganizationBudgetForm)
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
        foreach ($this->request->get(
            'recipient_organization_budget'
        ) as $recipientOrganizationBudgetIndex => $recipientOrganizationBudget) {
            $recipientOrganizationBudgetForm = sprintf(
                'recipient_organization_budget.%s',
                $recipientOrganizationBudgetIndex
            );
            $messages                        = array_merge(
                $messages,
                $this->addMessagesForPeriodStart(
                    $recipientOrganizationBudget['period_start'],
                    $recipientOrganizationBudgetForm
                ),
                $this->addMessagesForPeriodEnd(
                    $recipientOrganizationBudget['period_end'],
                    $recipientOrganizationBudgetForm
                ),
                $this->addMessagesForValue($recipientOrganizationBudget['value'], $recipientOrganizationBudgetForm),
                $this->addMessagesForNarrative(
                    $recipientOrganizationBudget['narrative'],
                    $recipientOrganizationBudgetForm
                )
            );
        }

        return $messages;
    }
}
