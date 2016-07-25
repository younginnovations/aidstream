<?php namespace App\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Auth;

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
        (!$userId) ?: $email_rule = sprintf('%s,%s', $email_rule, $userId);
        $username_rule = 'required|unique:users,username';
        (!$userId) ?: $username_rule = sprintf('%s,%s', $username_rule, $userId);

        $secondary_email_rule = '';

        if (Auth::user()->isAdmin()) {
            $secondary_user_id    = User::where('role_id', 7)->where('org_id', session('org_id'))->first();
            $secondary_email_rule = 'required|email|unique:users,email';
            (!$secondary_user_id) ?: $secondary_email_rule = sprintf('%s,%s', $secondary_email_rule, $secondary_user_id->id);
        }

        return [
            'login_username'  => $username_rule,
            'first_name'      => 'required',
            'last_name'       => 'required',
            'email'           => $email_rule,
            'time_zone'       => 'required',
            'profile_url'     => 'url',
            'profile_picture' => 'image',
            'secondary_email' => $secondary_email_rule
        ];
    }

    public function messages()
    {
        $messages = [];

        $messages['login_username.required']  = 'Username is required';
        $messages['login_username.unique']    = 'Sorry! This username has already been taken';
        $messages['first_name.required']      = 'First Name is required';
        $messages['last_name.required']       = 'Last Name is required';
        $messages['email.required']           = 'Email is required';
        $messages['email.email']              = 'Please enter valid email address';
        $messages['email.unique']             = 'Sorry! This email address has already been taken.';
        $messages['time_zone.required']       = 'Time Zone is required';
        $messages['profile_url.url']          = 'Please provide valid profile picture';
        $messages['profile_picture.image']    = 'Profile picture must be image';
        $messages['secondary_email.required'] = 'Secondary Contact email address is required';

        return $messages;
    }
}
