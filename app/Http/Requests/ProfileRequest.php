<?php namespace App\Http\Requests;

use App\User;

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
        $email_rule = 'required|email|unique:users,email';
        $userId     = $this->route()->id;
        $user       = app(User::class);
        (!$userId) ?: $email_rule = sprintf('%s,%s,email', $email_rule, $user->find($userId)->email);

        return [
            'first_name'           => 'required',
            'last_name'            => 'required',
            'email'                => $email_rule,
            'time_zone'            => 'required',
            'organization_name'    => 'required',
            'organization_address' => 'required',
            'country'              => 'required',
            'organization_url'     => 'url',
            'organization_logo'    => 'image'
        ];
    }
}
