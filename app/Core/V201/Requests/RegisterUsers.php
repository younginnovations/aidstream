<?php namespace app\Core\V201\Requests;

use App\Http\Requests\Request;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Core\V201\Traits\GetCodes;

/**
 * Class RegisterUsers
 * @package app\Core\V201\Requests
 */
class RegisterUsers extends Request
{
    use GetCodes;

    /**
     * RegisterUsers constructor.
     */
    public function __construct()
    {
        Validator::extend(
            'code_list',
            function ($attribute, $value, $parameters, $validator) {
                $listName = $parameters[1];
                $listType = $parameters[0];
                $codeList = $this->getCodes($listName, $listType);

                return in_array($value, $codeList);
            }
        );
        Validator::extend(
            'unique_email',
            function ($attribute, $value, $parameters, $validator) {
                $userEmails      = User::where('email', $value)->count();
                $secondaryEmails = Organization::whereRaw("secondary_contact ->> 'email' = ?", [$value])->count();

                return !($userEmails || $secondaryEmails);
            }
        );
    }

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

        $rules['first_name']        = 'required';
        $rules['last_name']         = 'required';
        $rules['email']             = 'required|email|unique_email';
        $rules['password']          = 'required|min:6';
        $rules['confirm_password']  = 'required|min:6|same:password';
        $rules['secondary_contact'] = 'required|email|unique_email';

        $rules = array_merge($rules, $this->getRulesForUsers($this->get('user')));

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['first_name.required']            = 'First Name is required.';
        $messages['last_name.required']             = 'Last Name is required.';
        $messages['email.required']                 = 'Email is required.';
        $messages['email.email']                    = 'Email is not valid.';
        $messages['email.unique_email']             = 'Email has already been taken.';
        $messages['password.required']              = 'Password is required.';
        $messages['password.min']                   = 'Password must be at least 6 characters.';
        $messages['confirm_password.required']      = 'Confirm Password is required.';
        $messages['confirm_password.min']           = 'Confirm Password must be at least 6 characters.';
        $messages['confirm_password.same']          = 'Passwords doesn\'t match.';
        $messages['secondary_contact.required']     = 'Secondary Contact is required.';
        $messages['secondary_contact.email']        = 'Secondary Contact Email is not valid.';
        $messages['secondary_contact.unique_email'] = 'Secondary Contact Email has already been taken.';

        $messages = array_merge($messages, $this->getMessagesForUsers($this->get('user')));

        return $messages;
    }

    /**
     * return validation rules for users
     * @param $users
     * @return array
     */
    protected function getRulesForUsers($users)
    {
        $users = (array) $users;
        $rules = [];

        $dbRoles = \DB::table('role')->select('id')->whereNotNull('permissions')->get();
        $roles   = [];
        foreach ($dbRoles as $role) {
            $roles[] = $role->id;
        }
        $roles = implode(',', $roles);

        foreach ($users as $userIndex => $user) {
            $rules[sprintf('user.%s.login_username', $userIndex)] = 'required|unique:users,username';
            $rules[sprintf('user.%s.email', $userIndex)]          = 'required|email|unique_email';
            $rules[sprintf('user.%s.first_name', $userIndex)]     = 'required';
            $rules[sprintf('user.%s.last_name', $userIndex)]      = 'required';
            $rules[sprintf('user.%s.role', $userIndex)]           = 'required|in:' . $roles;

        }

        return $rules;
    }

    /**
     * return validation messages for users
     * @param $users
     * @return array
     */
    protected function getMessagesForUsers($users)
    {
        $users    = (array) $users;
        $messages = [];

        foreach ($users as $userIndex => $user) {
            $messages[sprintf('user.%s.login_username.required', $userIndex)] = 'Username is required.';
            $messages[sprintf('user.%s.login_username.unique', $userIndex)]   = 'Username has already been taken.';
            $messages[sprintf('user.%s.email.required', $userIndex)]          = 'Email is required.';
            $messages[sprintf('user.%s.email.email', $userIndex)]             = 'Email is not valid.';
            $messages[sprintf('user.%s.email.unique_email', $userIndex)]      = 'Email has already been taken.';
            $messages[sprintf('user.%s.first_name.required', $userIndex)]     = 'First Name is required.';
            $messages[sprintf('user.%s.last_name.required', $userIndex)]      = 'Last Name is required.';
            $messages[sprintf('user.%s.role.required', $userIndex)]           = 'Role is required.';
            $messages[sprintf('user.%s.role.in', $userIndex)]                 = 'Role is not valid.';
        }

        return $messages;
    }
}
