<?php namespace App\Np\Services\Validation\Rules\V202;

use App\Core\V201\Traits\GetCodes;
use Illuminate\Support\Facades\DB;

/**
 * Class Users
 * @package App\Np\Services\Validation\Rules\V202
 */
class Users
{
    use GetCodes;

    /**
     * Lists of method that needs to be called.
     *
     * @var array
     */
    protected $methods = [
        'FirstName',
        'LastName',
        'Email',
        'UserName',
        'Password',
        'Role',
    ];

    /**
     * Contains rules for the users.
     *
     * @var array
     */
    protected $userRules = [];

    /**
     * Contains messages for the users.
     *
     * @var array
     */
    protected $userMessages = [];

    /**
     * Generates rules from the $this->methods listed.
     *
     * @return array
     */
    public function rules()
    {
        foreach ($this->methods() as $method) {
            $methodName = sprintf('rulesFor%s', $method);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        return $this->userRules;
    }

    /**
     * Generates messages from the $this->methods listed.
     *
     * @return array
     */
    public function messages()
    {
        foreach ($this->methods() as $method) {
            $methodName = sprintf('messagesFor%s', $method);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        return $this->userMessages;
    }

    /**
     * Return $this->methods;
     *
     * @return array
     */
    protected function methods()
    {
        return $this->methods;
    }

    /**
     * Sets rules for first_name of the user.
     */
    protected function rulesForFirstName()
    {
        $this->userRules['first_name'] = 'required';
    }

    /**
     * Sets messages for first_name of the user.
     */
    protected function messagesForFirstName()
    {
        $this->userMessages['first_name.required'] = trans('validation.required', ['attribute' => trans('lite/users.first_name')]);
    }

    /**
     * Sets rules for last_name of the user.
     */
    protected function rulesForLastName()
    {
        $this->userRules['last_name'] = 'required';
    }

    /**
     * Sets messages for last_name of the user.
     */
    protected function messagesForLastName()
    {
        $this->userMessages['last_name.required'] = trans('validation.required', ['attribute' => trans('lite/users.last_name')]);
    }

    /**
     * Sets rules for the email of the user.
     */
    protected function rulesForEmail()
    {
        $baseRequest = 'required|email|unique:%s';

        if ($userId = request()->route()->id) {
            $baseRequest = sprintf($baseRequest, 'users,' . $userId);
        } else {
            $baseRequest = sprintf($baseRequest, 'users');
        }

        $this->userRules['email'] = $baseRequest;
    }

    /**
     * Sets messages for the email of the user.
     */
    protected function messagesForEmail()
    {
        $this->userMessages['email.required'] = trans('validation.required', ['attribute' => trans('lite/users.email_address')]);
        $this->userMessages['email.email']    = trans('validation.email', ['attribute' => trans('lite/users.email_address')]);
        $this->userMessages['email.unique']   = trans('validation.unique', ['attribute' => trans('lite/users.email_address')]);
    }

    /**
     * Sets rules for the username of the user.
     */
    protected function rulesForUsername()
    {
        $baseRequest = 'required|unique:%s';

        if ($userId = request()->route()->id) {
            $baseRequest = sprintf($baseRequest, 'users,' . $userId);
        } else {
            $baseRequest = sprintf($baseRequest, 'users');
        }

        $this->userRules['username'] = $baseRequest;
    }

    /**
     * Sets messages for the username of the user.
     */
    protected function messagesForUsername()
    {
        $this->userMessages['username.required'] = trans('validation.required', ['attribute' => trans('lite/users.username')]);
        $this->userMessages['username.unique']   = trans('validation.unique', ['attribute' => trans('lite/users.username')]);
    }

    /**
     * Sets rules for the password of the user.
     */
    protected function rulesForPassword()
    {
        $this->userRules['password'] = 'required|min:6|confirmed';
    }

    /**
     * Sets messages for the password of the user.
     */
    protected function messagesForPassword()
    {
        $this->userMessages['password.required']  = trans('validation.required', ['attribute' => trans('lite/users.password')]);
        $this->userMessages['password.min']       = trans('validation.min.string', ['attribute' => trans('lite/users.password'), 'min' => 6]);
        $this->userMessages['password.confirmed'] = trans('validation.confirmed', ['attribute' => trans('lite/users.password')]);
    }

    /**
     * Sets rules for the roles of the user.
     */
    protected function rulesForRole()
    {
        $this->userRules['role_id'] = sprintf('required|in:%s', $this->roles());
    }

    /**
     * Sets messages for the role of the user.
     */
    protected function messagesForRole()
    {
        $this->userMessages['role_id.required'] = trans('validation.required', ['attribute' => trans('lite/users.permission')]);
        $this->userMessages['role_id.in']       = trans('validation.code_list', ['attribute' => trans('lite/users.permission')]);
    }

    /**
     * Returns list of the roles present in the role table of the database.
     *
     * @return string
     */
    protected function roles()
    {
        $dbRoles = DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $roles   = [];

        foreach ($dbRoles as $role) {
            $roles[] = $role->id;
        }

        return implode(",", $roles);
    }
}

