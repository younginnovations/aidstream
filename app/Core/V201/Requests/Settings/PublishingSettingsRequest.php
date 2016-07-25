<?php namespace App\Core\V201\Requests\Settings;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class PublishingSettingsRequest extends Request
{
    public function __construct()
    {
        Validator::extendImplicit(
            'include_operators',
            function ($attribute, $value, $parameters, $validator) {
                $value = $this->get('publisherId');

                return preg_match('/[a-zA-Z0-9-_]/', $value);
            }
        );
    }

    public function rules()
    {
        $rules                = [];
        $rules['publisherId'] = 'include_operators';

        return $rules;

    }

    public function messages()
    {
        $messages                                   = [];
        $messages['publisher_id.include_operators'] = 'Please enter valid publisher id';

        return $messages;
    }
}