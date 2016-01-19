<?php namespace App\Http\Requests;

class ProfileRequest extends Request
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
            'first_name'             => 'required',
            'last_name'              => 'required',
            'email'                  => 'required|email',
            'time_zone'              => 'required',
            'organization_name'      => 'required',
            'organization_address'   => 'required',
            'country'                => 'required',
            'organization_telephone' => 'numeric',
            'organization_url'       => 'url',
        ];
    }
}
