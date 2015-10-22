<?php

namespace Test\Smoke\BackEnd;

use Test\Smoke\AidStreamUserSmokeTestCase;

/**
 * Class AdminUserTest
 * @package Tests\Smoke\BackEnd
 */
class AdminUserTest extends AidStreamUserSmokeTestCase
{
    /**
     * @var string
     */
    protected $userRole = 'admin';

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

    /**
     * List all your admin role paths below
     *
     * @return array
     */
    public function providerAdminPaths()
    {
        $adminPaths = [
            ['settings', 200],
            ['activity', 200],
            ['activity/create', 200],
            ['activity/1', 200],
            ['activity/1/reporting-organization', 200],
            ['activity/1/iati-identifier', 200],
            //['activity/1/other-identifier', 200],
            //['activity/1/title', 200],
            ['organization/1', 200],
            ['organization/1/reportingOrg', 200],
            ['organization/1/identifier', 200],
            ['organization/1/name', 200],
            ['organization/1/total-budget', 200],
            ['organization/1/recipient-organization-budget', 200],
            ['organization/1/recipient-country-budget', 200],
            ['organization/1/document-link', 200],
            ['list-published-files', 200],
            ['admin/list-users', 200],
            ['admin/register-user', 200],
            ['admin/view-profile/3', 200],
            ['admin/reset-user-password/3', 200],
            ['not-existing', 404],
        ];

        return $adminPaths;
    }

    /**
     *
     * @throws \Exception
     */
    public function testAdminPageAccessIsOk()
    {
        $this->printSeparator();
        $this->printLn(sprintf('Testing for : %s', $this->baseUrl));

        foreach ($this->providerAdminPaths() as $adminPath) {
            $this->printLn(
                sprintf(
                    'Testing path %s to have response code %s for user %s, role %s',
                    $adminPath[0],
                    $adminPath[1],
                    $this->user['identifier'],
                    $this->user['role']
                )
            );
            $this->makeAuthenticatedCall('GET', $adminPath[0], $adminPath[1]);
        }
    }

}
