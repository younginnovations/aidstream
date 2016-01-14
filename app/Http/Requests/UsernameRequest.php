<?php namespace App\Http\Requests;

class UsernameRequest extends Request
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
     * User validation
     *
     * @return array
     */
    public function rules()
    {
        return [
            'organization_user_identifier' => 'required|max:255|unique:organizations,user_identifier',
            'username'                     => 'required|max:255|unique:users',
        ];
    }
}
