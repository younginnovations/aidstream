<?php namespace App\Core\V201\Requests\Activity;


/**
 * Class IatiIdentifierRequest
 * @package App\Core\V201\Requests\Activity
 */
class IatiIdentifierRequest extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules                         = [];
        $rules['activity_identifier']  = 'required';
        $rules['iati_identifier_text'] = 'required';

        return $rules;
    }
}
