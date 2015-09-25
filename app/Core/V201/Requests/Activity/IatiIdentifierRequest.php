<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

class IatiIdentifierRequest extends Request
{

    protected $redirect;

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
        $rules                         = [];
        $rules['activity_identifier']  = 'required';
        $rules['iati_identifier_text'] = 'required';

        return $rules;
    }
}
