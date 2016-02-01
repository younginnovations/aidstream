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
            ['activity/35', 200],
            ['activity/35/reporting-organization', 200],
            ['activity/35/iati-identifier', 200],
            ['activity/35/other-identifier', 200],
            ['activity/35/title', 200],
            ['activity/35/description', 200],
            ['activity/35/activity-status', 200],
            ['activity/35/activity-date', 200],
            ['activity/35/contact-info', 200],
            ['activity/35/activity-scope', 200],
            ['activity/35/participating-organization', 200],
            ['activity/35/recipient-country', 200],
            ['activity/35/recipient-region', 200],
            ['activity/35/location', 200],
            ['activity/35/sector', 200],
            ['activity/35/policy-maker', 200],
            ['activity/35/collaboration-type', 200],
            ['activity/35/default-flow-type', 200],
            ['activity/35/default-finance-type', 200],
            ['activity/35/default-aid-type', 200],
            ['activity/35/default-tied-status', 200],
            ['activity/35/country-budget-items', 200],
            ['activity/35/budget', 200],
            ['activity/35/planned-disbursement', 200],
            ['activity/35/transaction', 200],
            ['activity/35/transaction/create', 200],
            ['activity/35/transaction-upload', 200],
            ['activity/35/capital-spend', 200],
            ['activity/35/document-link', 200],
            ['activity/35/condition', 200],
            ['activity/35/result', 200],
            ['activity/35/result/create', 200],
            ['activity/35/legacy-data', 200],
            ['wizard/activity/create', 200],
            ['wizard/activity/1/title-description', 200],
            ['wizard/activity/1/date-status', 200],
            ['activity-upload', 200],
            ['organization/1', 200],
            ['organization/1/reportingOrg', 200],
            ['organization/1/identifier', 200],
            ['organization/1/name', 200],
            ['organization/1/total-budget', 200],
            ['organization/1/recipient-organization-budget', 200],
            ['organization/1/recipient-region-budget', 200],
            ['organization/1/recipient-country-budget', 200],
            ['organization/1/total-expenditure', 200],
            ['organization/1/document-link', 200],
            ['list-published-files', 200],
            ['admin/list-users', 200],
            ['admin/register-user', 200],
            ['admin/view-profile/5', 200],
            ['admin/reset-user-password/5', 200],
            ['admin/edit-user-permission/5', 200],
            ['change-activity-default/35', 200],
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
