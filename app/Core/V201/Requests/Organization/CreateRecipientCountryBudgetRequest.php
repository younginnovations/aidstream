<?php namespace App\Core\V201\Requests\Organization;

/**
 * Class CreateRecipientCountryBudgetRequest
 * @package App\Core\V201\Requests\Organization
 */
class CreateRecipientCountryBudgetRequest extends OrganizationBaseRequest
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
            'recipient_country_budget'
        ) as $recipientCountryBudgetIndex => $recipientCountryBudget) {
            $recipientCountryBudgetForm = sprintf('recipient_country_budget.%s', $recipientCountryBudgetIndex);
            $rules                      = array_merge(
                $rules,
                $this->getRecipientCountryBudgetRules(
                    $recipientCountryBudget['recipient_country'],
                    $recipientCountryBudgetForm
                ),
                $this->getRulesForPeriodStart($recipientCountryBudget['period_start'], $recipientCountryBudgetForm),
                $this->getRulesForPeriodEnd($recipientCountryBudget['period_end'], $recipientCountryBudgetForm),
                $this->getRulesForValue($recipientCountryBudget['value'], $recipientCountryBudgetForm),
                $this->getRulesForBudgetLine($recipientCountryBudget['budget_line'], $recipientCountryBudgetForm)
            );
        }

        return $rules;
    }

    /**
     * return validation messages to the rules
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->get(
            'recipient_country_budget'
        ) as $recipientCountryBudgetIndex => $recipientCountryBudget) {
            $recipientCountryBudgetForm = sprintf('recipient_country_budget.%s', $recipientCountryBudgetIndex);
            $messages                   = array_merge(
                $messages,
                $this->getRecipientCountryBudgetMessages(
                    $recipientCountryBudget['recipient_country'],
                    $recipientCountryBudgetForm
                ),
                $this->getMessagesForPeriodStart($recipientCountryBudget['period_start'], $recipientCountryBudgetForm),
                $this->getMessagesForPeriodEnd($recipientCountryBudget['period_end'], $recipientCountryBudgetForm),
                $this->getMessagesForValue($recipientCountryBudget['value'], $recipientCountryBudgetForm),
                $this->getMessagesBudgetLine($recipientCountryBudget['budget_line'], $recipientCountryBudgetForm)
            );
        }

        return $messages;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    public function getRecipientCountryBudgetRules(array $formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                             = sprintf('%s.recipient_country.%s', $formBase, $recipientCountryIndex);
            $rules[sprintf('%s.code', $recipientCountryForm)] = 'required';
            $rules                                            = array_merge(
                $rules,
                $this->getRulesForNarrative($recipientCountry['narrative'], $recipientCountryForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    public function getRecipientCountryBudgetMessages(array $formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                                         = sprintf('%s.recipient_country.%s', $formBase, $recipientCountryIndex);
            $messages[sprintf('%s.code.required', $recipientCountryForm)] = 'Code is required';
            $messages                                                     = array_merge(
                $messages,
                $this->getMessagesForNarrative($recipientCountry['narrative'], $recipientCountryForm)
            );
        }

        return $messages;
    }
}
