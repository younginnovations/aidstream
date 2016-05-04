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
        return $this->getRulesForRecipientRegion($this->get('recipient_region'));
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForRecipientRegion($this->get('recipient_region'));
    }

    /**
     * returns rules for recipient region
     * @param $formFields
     * @return array|mixed
     */
    public function getRulesForRecipientRegion($formFields)
    {
        $rules = [];
        $val   = $this->getRulesForMultipleRecipientRegion($formFields);

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                          = 'recipient_region.' . $recipientRegionIndex;
            $rules[$recipientRegionForm . '.region_code'] = 'required';
            $rules[$recipientRegionForm . '.percentage']  = 'numeric|max:100';
            if (count($formFields) > 1) {
                $rules[$recipientRegionForm . '.percentage'] = 'required|numeric|max:100';
            }
            if (!$val) {
                $rules[$recipientRegionForm . '.percentage'] = 'required|numeric|max:100|digits:100';
            }
            $rules = array_merge(
                $rules,
                $this->getRulesForNarrative(
                    $recipientRegion['narrative'],
                    $recipientRegionForm
                )
            );
        }

        return $rules;
    }

    /**
     * if recipient region has more than one block, percentage must be 100.
     * @param $formFields
     * @return bool
     */
    protected function getRulesForMultipleRecipientRegion($formFields)
    {
        $sum = 0;
        if (count($formFields) > 1) {
            foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
                $percentage = $recipientRegion['percentage'];
                $sum += $percentage;
            }

            if ($sum > 100 || $sum < 100) {
                return false;
            }
        }

        return true;
    }

    /**
     * returns messages for recipient region m
     * @param $formFields
     * @return array|mixed
     */
    public function getMessagesForRecipientRegion($formFields)
    {
        $messages = [];

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                                      = 'recipient_region.' . $recipientRegionIndex;
            $messages[$recipientRegionForm . '.region_code.required'] = 'Recipient region code is required';
            $messages[$recipientRegionForm . '.percentage.numeric']   = 'Percentage should be numeric.';
            $messages[$recipientRegionForm . '.percentage.max']       = 'Percentage should be less than or equal to 100';
            $messages[$recipientRegionForm . '.percentage.required']  = 'Percentage is required.';
            $messages[$recipientRegionForm . '.percentage.digits']    = 'Total sum of percentage must be 100';
            $messages                                                 = array_merge(
                $messages,
                $this->getMessagesForNarrative(
                    $recipientRegion['narrative'],
                    $recipientRegionForm
                )
            );
        }

        return $messages;
    }
}
