<?php namespace App\Core\V201\Wizard\Requests\Activity;

use App\Http\Requests\Request;

class IatiIdentifier extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules                        = [];
        $rules['activity_identifier'] = 'required';

        return $rules;
    }
}
