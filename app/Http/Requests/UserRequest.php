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
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'username'   => 'required|max:255|unique:users',
            'password'   => 'required|confirmed|min:6',
            'permission' => 'required|in:2,5,6,7'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => trans('validation.required', ['attribute' => trans('user.first_name')]),
            'first_name.max'      => trans('validation.max.string', ['attribute' => trans('user.first_name'), 'max' => 255]),
            'last_name.required'  => trans('validation.required', ['attribute' => trans('user.last_name')]),
            'last_name.max'       => trans('validation.max.string', ['attribute' => trans('user.last_name'), 'max' => 255]),
            'email.required'      => trans('validation.required', ['attribute' => trans('user.email_address')]),
            'email.email'         => trans('validation.email', ['attribute' => trans('user.email_address')]),
            'email.max'           => trans('validation.max.string', ['attribute' => trans('user.email'), 'max' => 255]),
            'email.unique'        => trans('validation.custom_unique', ['attribute' => trans('user.email')]),
            'username.required'   => trans('validation.required', ['attribute' => trans('user.username')]),
            'username.max'        => trans('validation.max.string', ['attribute' => trans('user.username'), 'max' => 255]),
            'username.unique'     => trans('validation.custom_unique', ['attribute' => trans('user.username')]),
            'password.required'   => trans('validation.required', ['attribute' => trans('user.password')]),
            'password.confirmed'  => trans('validation.confirmed', ['attribute' => trans('user.password')]),
            'password.min'        => trans('validation.min.string', ['attribute' => trans('user.password')]),
            'permission.required' => trans('validation.required', ['attribute' => trans('user.permission')]),
            'permissions.not_in'  => trans('validation.not_in', ['attribute' => trans('user.permission')])
        ];
    }
}
