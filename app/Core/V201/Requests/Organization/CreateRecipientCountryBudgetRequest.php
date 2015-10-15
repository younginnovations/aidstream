<?php namespace App\Core\V201\Requests\Organization;

use App\Models\OrganizationData;
use Illuminate\Foundation\Http\FormRequest;

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
                $this->addRulesForPeriodStart($recipientCountryBudget['period_start'], $recipientCountryBudgetForm),
                $this->addRulesForPeriodEnd($recipientCountryBudget['period_end'], $recipientCountryBudgetForm),
                $this->addRulesForValue($recipientCountryBudget['value'], $recipientCountryBudgetForm),
                $this->addRulesForBudgetLine($recipientCountryBudget['budget_line'], $recipientCountryBudgetForm)
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
            $rules                                                                            = $this->addRulesForNarrative(
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
                $this->addMessagesForPeriodStart($recipientCountryBudget['period_start'], $recipientCountryBudgetForm),
                $this->addMessagesForPeriodEnd($recipientCountryBudget['period_end'], $recipientCountryBudgetForm),
                $this->addMessagesForValue($recipientCountryBudget['value'], $recipientCountryBudgetForm),
                $this->addMessagesBudgetLine($recipientCountryBudget['budget_line'], $recipientCountryBudgetForm)
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
            $messages             = $this->addMessagesForNarrative(
                $recipientCountry['narrative'],
                $recipientCountryForm
            );
        }

        return $messages;
    }
}
