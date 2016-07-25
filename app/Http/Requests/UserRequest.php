<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
            'first_name'  => 'required|max:255',
            'last_name'   => 'required|max:255',
            'email'       => 'required|email|max:255|unique:users',
            'username'    => 'required|max:255|unique:users',
            'password'    => 'required|confirmed|min:6',
            'permissions' => 'required|not_in:1,3,4'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.max'      => 'First name cannot exceed more than 255 characters',
            'last_name.required'  => 'Last name is required',
            'last_name.max'       => 'Last name cannot exceed more than 255 characters',
            'email.required'      => 'Email address is required',
            'email.email'         => 'Please enter valid email address',
            'email.max'           => 'Email cannot exceed more than 255 characters',
            'email.unique'        => 'Sorry entered email address is already taken',
            'username.required'   => 'Username is required',
            'username.max'        => 'Username cannot exceed more than 255 characters',
            'username.unique'     => 'Sorry entered username is already taken',
            'password.required'   => 'Password is required',
            'password.confirmed'  => "Sorry password didn't matched with confirmed one",
            'password.min'        => 'Password must be minimum of 6 characters',
            'permission.required' => 'Permission is required ',
            'permissions.not_in'  => 'Please select valid permissions'
        ];
    }
}
