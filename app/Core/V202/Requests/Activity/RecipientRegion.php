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
     * @param $recipientCountry
     * @return array|mixed
     */
    public function getRulesForRecipientRegion($formFields, $recipientCountry)
    {
        $rules = [];

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                             = 'recipient_region.' . $recipientRegionIndex;
            $rules[$recipientRegionForm . '.region_code']    = 'required';
            $rules[$recipientRegionForm . '.vocabulary_uri'] = 'url';
            $rules[$recipientRegionForm . '.percentage']     = 'numeric|max:100';
            if (count($formFields) > 1 || $recipientCountry != null) {
                $rules[$recipientRegionForm . '.percentage'] = 'required|numeric|max:100';
            }
            $rules = array_merge($rules, $this->getRulesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
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
            $messages[$recipientRegionForm . '.region_code.required'] = trans('validation.required', ['attribute' => trans('elementForm.recipient_region_code')]);
            $messages[$recipientRegionForm . '.vocabulary_uri.url']   = trans('validation.url');
            $messages[$recipientRegionForm . '.percentage.numeric']   = trans('validation.numeric', ['attribute' => trans('elementForm.percentage')]);
            $messages[$recipientRegionForm . '.percentage.max']       = trans('validation.max.numeric', ['attribute' => trans('elementForm.percentage'), 'max' => 100]);
            $messages[$recipientRegionForm . '.percentage.required']  = trans('validation.required', ['attribute' => trans('elementForm.percentage')]);
            $messages                                                 = array_merge($messages, $this->getMessagesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
        }

        return $messages;
    }
}
