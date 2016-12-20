<?php namespace App\Core\V201\Requests;

use App\Http\Requests\Request;

/**
 * Class Password
 * @package app\Core\V201\Requests
 */
class Password extends Request
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
        $rules = [];

        $rules['password']         = 'required|min:6';
        $rules['confirm_password'] = 'required|min:6|same:password';

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['password.required']         = trans('validation.required', ['attribute' => trans('global.password')]);
        $messages['password.min']              = trans('validation.min.string', ['attribute' => trans('global.password'), 'min' => 6]);
        $messages['confirm_password.required'] = trans('validation.required', ['attribute' => trans('user.confirm_password')]);
        $messages['confirm_password.min']      = trans('validation.min.string', ['attribute' => trans('user.confirm_password'), 'min' => 6]);
        $messages['confirm_password.same']     = trans('validation.same', ['attribute' => trans('global.password')]);

        return $messages;
    }
}
