<?php namespace App\Services\RequestManager;

use App\Http\Requests\Request;

/**
 * Class Contact
 * @package App\Services\RequestManager
 */
class Contact extends Request
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

        $rules['full_name'] = 'required';
        $rules['email']     = 'required|email';
        $rules['message']   = 'required';

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['full_name.required'] = 'Full Name is required.';
        $messages['email.required']     = 'Email is required.';
        $messages['email.email']        = 'Please enter a valid email address.';
        $messages['message.required']   = 'Message is required.';

        return $messages;
    }
}
