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
        return $this->getRulesForRecipientCountry($this->get('recipient_country'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForRecipientCountry($this->get('recipient_country'));
    }

    /**
     * returns rules for recipient country form
     * @param $formFields
     * @return array
     */
    public function getRulesForRecipientCountry($formFields)
    {
        $rules = [];
        $val   = $this->getRulesForMultipleRecipientCountry($formFields);

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                           = 'recipient_country.' . $recipientCountryIndex;
            $rules[$recipientCountryForm . '.country_code'] = 'required';
            $rules[$recipientCountryForm . '.percentage']   = 'numeric|max:100';
            if (count($formFields) > 1) {
                $rules[$recipientCountryForm . '.percentage'] = 'required|numeric|max:100';
            }
            if (!$val) {
                $rules[$recipientCountryForm . '.percentage'] = 'required|numeric|max:100|digits:100';
            }
            $rules = array_merge(
                $rules,
                $this->getRulesForNarrative(
                    $recipientCountry['narrative'],
                    $recipientCountryForm
                )
            );
        }

        return $rules;
    }

    /**
     * if recipient country has more than one block, percentage must be 100.
     * @param $formFields
     * @return bool
     */
    protected function getRulesForMultipleRecipientCountry($formFields)
    {
        $sum = 0;
        if(count($formFields) > 1) {
            foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
                $percentage = $recipientCountry['percentage'];
                $sum += $percentage;
            }

            if ($sum > 100 || $sum < 100) {
                return false;
            }
        }

        return true;
    }

    /**
     * returns messages for recipient country form rules
     * @param $formFields
     * @return array
     */
    public function getMessagesForRecipientCountry($formFields)
    {
        $messages = [];

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                                       = 'recipient_country.' . $recipientCountryIndex;
            $messages[$recipientCountryForm . '.country_code.required'] = 'Country code field is required';
            $messages[$recipientCountryForm . '.percentage.numeric']    = 'Percentage must be a number';
            $messages[$recipientCountryForm . '.percentage.max']        = 'Percentage may not be greater than 100';
            $messages[$recipientCountryForm . '.percentage.required']   = 'Percentage is required';
            $messages[$recipientCountryForm . '.percentage.digits']     = 'Total sum of percentage must be 100';
            $messages                                                   = array_merge(
                $messages,
                $this->getMessagesForNarrative(
                    $recipientCountry['narrative'],
                    $recipientCountryForm
                )
            );
        }

        return $messages;
    }
}
