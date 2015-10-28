<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Requests\Activity
 */
class RecipientCountry extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForRecipientCountry($this->request->get('recipient_country'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForRecipientCountry($this->request->get('recipient_country'));
    }

    /**
     * returns rules for recipient country form
     * @param $formFields
     * @return array
     */
    public function addRulesForRecipientCountry($formFields)
    {
        $rules = [];

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                           = 'recipient_country.' . $recipientCountryIndex;
            $rules[$recipientCountryForm . '.country_code'] = 'required';
            $rules[$recipientCountryForm . '.percentage']   = 'numeric|max:100';
            $rules                                          = array_merge(
                $rules,
                $this->addRulesForNarrative(
                    $recipientCountry['narrative'],
                    $recipientCountryForm
                )
            );
        }

        return $rules;
    }

    /**
     * returns messages for recipient country form rules
     * @param $formFields
     * @return array
     */
    public function addMessagesForRecipientCountry($formFields)
    {
        $messages = [];

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                                       = 'recipient_country.' . $recipientCountryIndex;
            $messages[$recipientCountryForm . '.country_code.required'] = 'Country code field is required';
            $messages[$recipientCountryForm . '.percentage.numeric']    = 'Percentage must be a number';
            $messages[$recipientCountryForm . '.percentage.max']        = 'Percentage may not be greater than 100';
            $messages                                                   = array_merge(
                $messages,
                $this->addMessagesForNarrative(
                    $recipientCountry['narrative'],
                    $recipientCountryForm
                )
            );
        }

        return $messages;
    }
}
