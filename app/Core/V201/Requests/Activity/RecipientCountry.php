<?php namespace App\Core\V201\Requests\Activity;

use App\Services\Activity\RecipientRegionManager;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Requests\Activity
 */
class RecipientCountry extends ActivityBaseRequest
{

    protected $recipientRegion;

    public function __construct(RecipientRegionManager $recipientRegion)
    {
        parent::__construct();
        $this->recipientRegion = $recipientRegion;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $activityId      = $this->segment(2);
        $recipientRegion = $this->recipientRegion->getRecipientRegionData($activityId);

        return $this->getRulesForRecipientCountry($this->get('recipient_country'), $recipientRegion);
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
     * @param $recipientRegion
     * @return array
     */
    public function getRulesForRecipientCountry($formFields, $recipientRegion)
    {
        $rules = [];

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                           = 'recipient_country.' . $recipientCountryIndex;
            $rules[$recipientCountryForm . '.country_code'] = 'required';
            $rules[$recipientCountryForm . '.percentage']   = 'numeric|max:100';
            if (count($formFields) > 1 || $recipientRegion != null) {
                $rules[$recipientCountryForm . '.percentage'] = 'required|numeric|max:100';
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
     * returns messages for recipient country form rules
     * @param $formFields
     * @return array
     */
    public function getMessagesForRecipientCountry($formFields)
    {
        $messages = [];

        foreach ($formFields as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryForm                                       = 'recipient_country.' . $recipientCountryIndex;
            $messages[$recipientCountryForm . '.country_code.required'] = trans('validation.required', ['attribute' => trans('elementForm.country_code')]);
            $messages[$recipientCountryForm . '.percentage.numeric']    = trans('validation.numeric', ['attribute' => trans('elementForm.percentage')]);
            $messages[$recipientCountryForm . '.percentage.max']        = trans('validation.max.numeric', ['attribute' => trans('elementForm.percentage')]);
            $messages[$recipientCountryForm . '.percentage.required']   = trans('validation.required', ['attribute' => trans('elementForm.percentage')]);
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
