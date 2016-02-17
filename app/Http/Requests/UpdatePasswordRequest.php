<?php namespace App\Http\Requests;

class UpdatePasswordRequest extends Request
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
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ];
    }
}
