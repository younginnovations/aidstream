<?php namespace App\SuperAdmin\Requests;

use App\Http\Requests\Request;

class OrganizationGroup extends Request
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
        $rules                                            = [];
        $orgGroup                                         = 'new_organization_group.0';
        $adminGroup                                       = 'group_admin_information.0';
        $rules[sprintf('%s.group_name', $orgGroup)]       = 'required';
        $rules[sprintf('%s.organizations', $orgGroup)]    = 'required';
        $rules[sprintf('%s.group_identifier', $orgGroup)] = 'required|unique:user_group,group_identifier';
        $rules[sprintf('%s.first_name', $adminGroup)]     = 'required';
        $rules[sprintf('%s.last_name', $adminGroup)]      = 'required';
        $rules[sprintf('%s.email', $adminGroup)]          = 'required|email';
        $rules[sprintf('%s.password', $adminGroup)]       = 'required|confirmed|min:6';
        if ($this->method() === 'PUT') {
            $rules[sprintf('%s.group_identifier', $orgGroup)] = 'required';
        }

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages                                                     = [];
        $orgGroup                                                     = 'new_organization_group.0';
        $adminGroup                                                   = 'group_admin_information.0';
        $messages[sprintf('%s.group_name.required', $orgGroup)]       = 'Group name is required';
        $messages[sprintf('%s.organizations.required', $orgGroup)]    = 'Organizations are required';
        $messages[sprintf('%s.group_identifier.required', $orgGroup)] = 'Group identifier is required';
        $messages[sprintf('%s.group_identifier.unique', $orgGroup)]   = 'Group identifier has already been taken';
        $messages[sprintf('%s.first_name.required', $adminGroup)]     = 'First name is required';
        $messages[sprintf('%s.last_name.required', $adminGroup)]      = 'Last name is required';
        $messages[sprintf('%s.email.required', $adminGroup)]          = 'Email is required';
        $messages[sprintf('%s.email.email', $adminGroup)]             = 'Email should be valid';
        $messages[sprintf('%s.password.required', $adminGroup)]       = 'Password is required';
        $messages[sprintf('%s.password.min', $adminGroup)]            = 'Password minimum 6 character long';
        $messages[sprintf('%s.password.confirmed', $adminGroup)]      = 'Password do not match';

        return $messages;

    }
}
