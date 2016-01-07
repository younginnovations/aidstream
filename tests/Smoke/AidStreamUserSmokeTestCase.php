<?php

namespace Test\Smoke;

use YIPL\Hookah\Test\Smoke\BaseUserTestCase;

/**
 * Class AidStreamUserSmokeTestCase
 * @package Test\Smoke
 */
class AidStreamUserSmokeTestCase extends BaseUserTestCase
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://newstage.aidstream.org/';

    /**
     * @var string
     */
    protected $loginPath = 'auth/login';

    /**
     * @var string
     */
    protected $usernameField = 'login';

    /**
     * @var string
     */
    protected $passwordField = 'password';

    /**
     * @var string
     */
    protected $submitButtonText = 'Login';

    /**
     * @var array
     */
    protected $users = [];

    /**
     * @var string
     */
    protected $loggedInLinkText = 'Logout';

    /**
     * Constructor
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, $data = [], $dataName = '')
    {
        $this->setUsers();
        parent::__construct($name, $data, $dataName);
    }

    /**
     * set user credentials form env
     */
    protected function setUsers()
    {
        $this->users = [['role' => 'admin', 'identifier' => env('LOGIN_USER'), 'password' => env('LOGIN_PASSWORD')]];
    }
}
