<?php namespace App\Core\V201\Requests\Organization;

class CreateRecipientCountryBudgetRequest extends OrganizationBaseRequest
{

    protected $redirect;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->request->get(
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

    public function getRecipientCountryBudgetRules(array $formfields, $formbase)
    {
        $rules = [];
        foreach ($formfields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                                                             = sprintf(
                '%s.recipient_country.%s',
                $formbase,
                $recipientCountryIndex
            );
            $rules[sprintf('%srecipient_country.%s.code', $formbase, $recipientCountryIndex)] = 'required';
            $rules                                                                            = $this->getRulesForNarrative(
                $recipientCountry['narrative'],
                $recipientCountryForm
            );
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get(
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

    public function getRecipientCountryBudgetMessages(array $formfields, $formbase)
    {
        $messages = [];
        foreach ($formfields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm = sprintf(
                '%s.recipient_country.%s',
                $formbase,
                $recipientCountryIndex
            );
            $messages[sprintf(
                '%s.recipient_country.%s.code.required',
                $formbase,
                $recipientCountryIndex
            )]                    = 'code is required';
            $messages             = $this->getMessagesForNarrative(
                $recipientCountry['narrative'],
                $recipientCountryForm
            );
        }

        return $messages;
    }
}
