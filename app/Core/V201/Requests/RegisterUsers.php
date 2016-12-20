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

        $messages['first_name.required']            = trans('validation.required', ['attribute' => trans('user.first_name')]);
        $messages['last_name.required']             = trans('validation.required', ['attribute' => trans('user.last_name')]);
        $messages['email.required']                 = trans('validation.required', ['attribute' => trans('user.email')]);
        $messages['email.email']                    = trans('validation.code_list', ['attribute' => trans('user.email')]);
        $messages['email.unique_email']             = trans('validation.custom_unique', ['attribute' => trans('user.email')]);
        $messages['password.required']              = trans('validation.required', ['attribute' => trans('global.password')]);
        $messages['password.min']                   = trans('validation.min.string', ['attribute' => trans('global.password'), 'min' => 6]);
        $messages['confirm_password.required']      = trans('validation.required', ['attribute' => trans('user.confirm_password')]);
        $messages['confirm_password.min']           = trans('validation.min.string', ['attribute' => trans('user.confirm_password'), 'min' => 6]);
        $messages['confirm_password.same']          = trans('validation.match', ['attribute' => trans('user.password')]);
        $messages['secondary_contact.required']     = trans('validation.required', ['attribute' => trans('user.secondary_contact')]);
        $messages['secondary_contact.email']        = trans('validation.code_list', ['attribute' => trans('user.secondary_contact_email')]);
        $messages['secondary_contact.unique_email'] = trans('validation.custom_unique', ['attribute' => trans('user.secondary_contact_email')]);

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
            $messages[sprintf('user.%s.login_username.required', $userIndex)] = trans('validation.required', ['attribute' => trans('user.username')]);
            $messages[sprintf('user.%s.login_username.unique', $userIndex)]   = trans('validation.custom_unique', ['attribute' => trans('user.username')]);
            $messages[sprintf('user.%s.email.required', $userIndex)]          = trans('validation.required', ['attribute' => trans('user.email')]);
            $messages[sprintf('user.%s.email.email', $userIndex)]             = trans('validation.code_list', ['attribute' => trans('user.email')]);
            $messages[sprintf('user.%s.email.unique_email', $userIndex)]      = trans('validation.custom_unique', ['attribute' => trans('user.email')]);
            $messages[sprintf('user.%s.first_name.required', $userIndex)]     = trans('validation.required', ['attribute' => trans('user.first_name')]);
            $messages[sprintf('user.%s.last_name.required', $userIndex)]      = trans('validation.required', ['attribute' => trans('user.last_name')]);
            $messages[sprintf('user.%s.role.required', $userIndex)]           = trans('validation.required', ['attribute' => trans('user.role')]);
            $messages[sprintf('user.%s.role.in', $userIndex)]                 = trans('validation.code_list', ['attribute' => trans('user.role')]);
        }

        return $messages;
    }
}
