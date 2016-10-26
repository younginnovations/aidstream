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

        $messages['password.required']         = 'Password is required.';
        $messages['password.min']              = 'Password must be at least 6 characters.';
        $messages['confirm_password.required'] = 'Confirm Password is required.';
        $messages['confirm_password.min']      = 'Confirm Password must be at least 6 characters.';
        $messages['confirm_password.same']     = 'Passwords doesn\'t match.';

        return $messages;
    }
}
