<?php namespace App\Np\Services\Validation\Rules\V202;

use App\Core\V201\Traits\GetCodes;

/**
 * Class Profile
 * @package App\Np\Services\Validation\Rules\V202
 */
class Profile
{
    use GetCodes;

    /**
     * @var array
     */
    protected $profileRules = [];

    /**
     * Methods Template
     *
     * @var array
     */
    protected $methods = [
        'Username',
        'Email',
        'FirstName',
        'LastName',
        'Permission',
        'SecondaryEmailAddress'
    ];

    /**
     * @var array
     */
    protected $profileMessages = [];

    /**
     * Rules maker
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

        return $this->profileRules;
    }

    /**
     * Messages maker
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

        return $this->profileMessages;
    }

    /**
     * Returns all the methods
     *
     * @return array
     */
    protected function methods()
    {
        return $this->methods;
    }

    /**
     * Rules for Username
     *
     * @return $this
     */
    protected function rulesForUsername()
    {
        $this->profileRules['userName'] = 'required';

        return $this;
    }

    /**
     * Messages for Username
     * @return $this
     */
    protected function messagesForUsername()
    {
        $this->profileMessages['userName.required'] = trans('validation.required', ['attribute' => trans('lite/profile.username')]);

        return $this;
    }

    /**
     * Rules for Email
     *
     * @return $this
     */
    protected function rulesForEmail()
    {
        $this->profileRules['email'] = 'required';

        return $this;
    }

    /**
     * Messages for Email
     *
     * @return $this
     */
    protected function messagesForEmail()
    {
        $this->profileMessages['email.required'] = trans('validation.required', ['attribute' => trans('lite/profile.email')]);

        return $this;
    }

    /**
     * Rules for FirstName
     *
     * @return $this
     */
    protected function rulesForFirstName()
    {
        $this->profileRules['firstName'] = 'required';

        return $this;
    }

    /**
     * Messages for FirstName
     *
     * @return $this
     */
    protected function messagesForFirstName()
    {
        $this->profileMessages['firstName.required'] = trans('validation.required', ['attribute' => trans('lite/profile.first_name')]);

        return $this;
    }

    /**
     * Rules for LastName
     *
     * @return $this
     */
    protected function rulesForLastName()
    {
        $this->profileRules['lastName'] = 'required';

        return $this;
    }

    /**
     * Messages for LastName
     *
     * @return $this
     */
    protected function messagesForLastName()
    {
        $this->profileMessages['lastName.required'] = trans('validation.required', ['attribute' => trans('lite/profile.last_name')]);

        return $this;
    }

    /**
     * Rules for Permission
     *
     * @return $this
     */
    protected function rulesForPermission()
    {
        $this->profileRules['permission'] = 'required';

        return $this;
    }

    /**
     * Messages for Permission
     *
     * @return $this
     */
    protected function messagesForPermission()
    {
        $this->profileMessages['permission.required'] = trans('validation.required', ['attribute' => trans('lite/profile.permission')]);

        return $this;
    }

    /**
     * Rules for Secondary Email Address
     *
     * @return $this
     */
    protected function rulesForSecondaryEmailAddress()
    {
        $this->profileRules['secondaryEmail'] = 'required';

        return $this;
    }

    /**
     * Rules for Secondary Email Address
     *
     * @return $this
     */
    protected function messagesForSecondaryEmailAddress()
    {
        $this->profileMessages['secondaryEmail.required'] = trans('validation.required', ['attribute' => trans('lite/profile.secondary') . ' ' . trans('lite/profile.email')]);

        return $this;
    }
}
