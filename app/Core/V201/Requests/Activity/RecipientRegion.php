<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Requests\Activity
 */
class RecipientRegion extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     * prepare the error message
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForRecipientRegion($this->request->get('recipient_region'));
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForRecipientRegion($this->request->get('recipient_region'));
    }

    /**
     * returns rules for recipient region
     * @param $formFields
     * @return array|mixed
     */
    public function addRulesForRecipientRegion($formFields)
    {
        $rules = [];

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                          = 'recipient_region.' . $recipientRegionIndex;
            $rules[$recipientRegionForm . '.region_code'] = 'required';
            $rules[$recipientRegionForm . '.percentage']  = 'numeric|max:100';
            $rules                                        = array_merge(
                $rules,
                $this->addRulesForNarrative(
                    $recipientRegion['narrative'],
                    $recipientRegionForm
                )
            );
        }

        return $rules;
    }

    /**
     * returns messages for recipient region m
     * @param $formFields
     * @return array|mixed
     */
    public function addMessagesForRecipientRegion($formFields)
    {
        $messages = []

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                                      = 'recipient_region.' . $recipientRegionIndex;
            $messages[$recipientRegionForm . '.region_code.required'] = 'Recipient region code is required';
            $messages[$recipientRegionForm . '.percentage.numeric']   = 'Percentage should be numeric.';
            $messages[$recipientRegionForm . '.percentage.max']       = 'Percentage should be less than or equal to 100';
            $messages                                                 = array_merge(
                $messages,
                $this->addMessagesForNarrative(
                    $recipientRegion['narrative'],
                    $recipientRegionForm
                )
            );
        }

        return $messages;
    }
}
