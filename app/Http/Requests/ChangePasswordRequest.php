<?php namespace App\Http\Requests;

class ChangePasswordRequest extends Request
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
            'old_password'          => 'required|min:6',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ];
    }
}
