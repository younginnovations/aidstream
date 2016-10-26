<?php namespace App\Core\V201\Requests\Settings;

use App\Http\Requests\Request;

class DefaultValuesRequest extends Request
{
    public function rules()
    {
        $rules                     = [];
        $rules['default_currency'] = 'required';
        $rules['default_language'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages                     = [];
        $messages['default_currency'] = 'Currency is required';
        $messages['default_language'] = 'Language is required';

        return $messages;
    }

}