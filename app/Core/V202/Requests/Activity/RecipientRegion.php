<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\RecipientRegion as V201RecipientRegion;

/**
 * Class RecipientRegion
 * @package App\Core\V202\Requests\Activity
 */
class RecipientRegion extends V201RecipientRegion
{
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
            $recipientRegionForm                             = 'recipient_region.' . $recipientRegionIndex;
            $rules[$recipientRegionForm . '.region_code']    = 'required';
            $rules[$recipientRegionForm . '.vocabulary_uri'] = 'url';
            $rules[$recipientRegionForm . '.percentage']     = 'numeric|max:100';
            if (count($formFields) > 1) {
                $rules[$recipientRegionForm . '.percentage'] = 'required|numeric|max:100';
            }
            if (!$val) {
                $rules[$recipientRegionForm . '.percentage'] = 'required|numeric|max:100|digits:100';
            }
            $rules                                           = array_merge($rules, $this->getRulesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
        }

        return $rules;
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
            $messages[$recipientRegionForm . '.vocabulary_uri.url']   = 'Enter valid URL. eg. http://example.com';
            $messages[$recipientRegionForm . '.percentage.numeric']   = 'Percentage should be numeric.';
            $messages[$recipientRegionForm . '.percentage.max']       = 'Percentage should be less than or equal to 100';
            $messages[$recipientRegionForm . '.percentage.required']  = 'Percentage is required.';
            $messages[$recipientRegionForm . '.percentage.digits']    = 'Total sum of percentage must be 100';
            $messages                                                 = array_merge($messages, $this->getMessagesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
        }

        return $messages;
    }
}
