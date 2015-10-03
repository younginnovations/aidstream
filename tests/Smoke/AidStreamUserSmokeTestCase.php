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
     *
     * @todo get username/password from env
     */
    protected $users =  [
        ['role'=> 'admin', 'identifier' => 'yipl_admin', 'password' => 'admin123'],
    ];

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
        parent::__construct($name, $data, $dataName);
    }
}
