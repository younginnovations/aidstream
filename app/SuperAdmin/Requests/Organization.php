<?php namespace App\SuperAdmin\Requests;

use App\Http\Requests\Request;

class Organization extends Request
{

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
     *
     * @return array
     */
    public function rules()
    {
        $rules                                                     = [];
        $orgInfo                                                   = 'organization_information.0';
        $adminInfo                                                 = 'admin_information.0';
        $defaultFieldValue                                         = 'default_field_values.0';
        $rules[sprintf('%s.name', $orgInfo)]                       = 'required';
        $rules[sprintf('%s.address', $orgInfo)]                    = 'required';
        $rules[sprintf('%s.user_identifier', $orgInfo)]            = 'required|unique:organizations,user_identifier';
        $rules[sprintf('%s.first_name', $adminInfo)]               = 'required';
        $rules[sprintf('%s.last_name', $adminInfo)]                = 'required';
        $rules[sprintf('%s.email', $adminInfo)]                    = 'required|email';
        $rules[sprintf('%s.password', $adminInfo)]                 = 'required|confirmed|min:6';
        $rules[sprintf('%s.password_confirmation', $adminInfo)]    = 'required';
        $rules[sprintf('%s.default_currency', $defaultFieldValue)] = 'required';
        $rules[sprintf('%s.default_language', $defaultFieldValue)] = 'required';
        if ($this->method() === 'PUT') {
            $rules[sprintf('%s.user_identifier', $orgInfo)] = 'required';
        }

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages                                                              = [];
        $orgInfo                                                               = 'organization_information.0';
        $adminInfo                                                             = 'admin_information.0';
        $defaultFieldValue                                                     = 'default_field_values.0';
        $messages[sprintf('%s.name.required', $orgInfo)]                       = 'Organization name is required';
        $messages[sprintf('%s.address.required', $orgInfo)]                    = 'Organization address is required';
        $messages[sprintf('%s.user_identifier.required', $orgInfo)]            = 'User identifier is required';
        $messages[sprintf('%s.user_identifier.unique', $orgInfo)]              = 'User identifier has already been taken';
        $messages[sprintf('%s.first_name.required', $adminInfo)]               = 'First name is required';
        $messages[sprintf('%s.last_name.required', $adminInfo)]                = 'Last name is required';
        $messages[sprintf('%s.email.required', $adminInfo)]                    = 'Email is required';
        $messages[sprintf('%s.email.email', $adminInfo)]                       = 'Email should be valid';
        $messages[sprintf('%s.password.required', $adminInfo)]                 = 'Password is required';
        $messages[sprintf('%s.password.min', $adminInfo)]                      = 'Password minimum 6 character long';
        $messages[sprintf('%s.password.confirmed', $adminInfo)]                = 'Password do not match';
        $messages[sprintf('%s.password_confirmation.required', $adminInfo)]    = 'Password confirmation is required';
        $messages[sprintf('%s.default_currency.required', $defaultFieldValue)] = 'Default currency is required';
        $messages[sprintf('%s.default_language.required', $defaultFieldValue)] = 'Default language is required';

        return $messages;
    }
}
