<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

class RecipientRegion extends Request
{

    protected $redirect;
    protected $messages;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * prepare the error message
     * @return array
     */
    public function rules()
    {
        $rules    = [];
        $messages = [];
        foreach ($this->request->get('recipient_region') as $recipientRegionIndex => $recipientRegion) {
            $rules['recipient_region.' . $recipientRegionIndex . '.region_code']          = 'required';
            $rules['recipient_region.' . $recipientRegionIndex . '.region_code.required'] = 'Recipient region code is required';
            $rules['recipient_region.' . $recipientRegionIndex . '.percentage']           = 'numeric|max:100';
            $rules['recipient_region.' . $recipientRegionIndex . '.percentage.numeric']   = 'Recipient region code should be numeric';
            $rules['recipient_region.' . $recipientRegionIndex . '.percentage.max']       = 'Recipient region code should be less than 100';
            foreach ($recipientRegion['narrative'] as $narrativeKey => $narrative) {
                $rules['recipient_region.' . $recipientRegionIndex . '.narrative.' . $narrativeKey . '.narrative']             = 'required';
                $messages['recipient_region.' . $recipientRegionIndex . '.narrative.' . $narrativeKey . '.narrative.required'] = 'Recipient Country narrative is required';
            }
        }
        $this->messages = $messages;

        return $rules;
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }
}
