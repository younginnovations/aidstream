<?php namespace App\SuperAdmin\Repositories;

use App\Models\Organization\Organization;
use App\Models\Settings;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin as SuperAdminInterface;
use App\User;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class SuperAdmin
 * @package App\SuperAdmin\Repositories
 */
class SuperAdmin implements SuperAdminInterface
{
    /**
     * @var Organization
     */
    protected $organization;
    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var DbLogger
     */
    protected $dbLogger;

    /**
     * @param User            $user
     * @param Settings        $settings
     * @param Organization    $organization
     * @param DatabaseManager $database
     * @param Logger          $logger
     * @param DbLogger        $dbLogger
     */
    public function __construct(
        User $user,
        Settings $settings,
        Organization $organization,
        DatabaseManager $database,
        Logger $logger,
        DbLogger $dbLogger
    ) {
        $this->organization = $organization;
        $this->database     = $database;
        $this->logger       = $logger;
        $this->settings     = $settings;
        $this->user         = $user;
        $this->dbLogger     = $dbLogger;
    }

    /**
     * get all organization data with their users and activities
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrganizations()
    {
        return $this->organization->with(['users', 'activities'])->orderBy('name', 'asc')->get();
    }

    /**
     * get organization by its id
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrganizationById($id)
    {
        return $this->organization->findOrFail($id);
    }

    /**
     * get organization with user information
     * @param $orgId
     * @return array
     */
    public function getOrganizationUserById($orgId)
    {
        return $organization = $this->organization->with('users')->findOrFail($orgId)->toArray();
    }

    /**
     * add or update organization by superadmin
     * @param null  $orgId
     * @param array $orgDetails
     * @return mixed|void
     */
    public function saveOrganization(array $orgDetails, $orgId = null)
    {
        try {
            $this->database->beginTransaction();

            $orgData      = $this->makeOrganizationData($orgDetails);
            $organization = $this->organization->firstOrNew(['id' => $orgId]);
            $organization->fill($orgData)->save();

            $adminData = $this->makeAdminData($orgDetails, $organization->id);
            $user      = $this->user->firstOrNew(['org_id' => $organization->id]);
            $user->fill($adminData)->save();

            $settingsData = $this->makeSettingsData($orgDetails, $organization->id);
            $settings     = $this->settings->firstOrNew(['organization_id' => $organization->id]);
            $settings->fill($settingsData)->save();

            $this->database->commit();

            $this->logger->info(($orgId) ? 'Organization information Updated' : 'Organization added');
            $this->dbLogger->activity(
                ($orgId) ? "organization_updated" : "organization_added",
                [
                    'user_id'         => $user->id,
                    'organization_id' => $orgId
                ]
            );
        } catch (Exception $exception) {
            $this->database->rollback();

            $this->logger->error(
                sprintf(
                    'organization information could no be %s due to %s',
                    ($orgId) ? 'updated' : 'added',
                    $exception->getMessage()
                ),
                [
                    'settings' => $orgDetails,
                    'trace'    => $exception->getTraceAsString()
                ]
            );
        }
    }

    protected function makeOrganizationData(array $orgDetails)
    {
        $orgData = [
            'name'            => $orgDetails['organization_information'][0]['name'],
            'address'         => $orgDetails['organization_information'][0]['address'],
            'user_identifier' => $orgDetails['organization_information'][0]['user_identifier'],
        ];

        return $orgData;
    }

    protected function makeAdminData(array $orgDetails, $orgId)
    {
        $adminData = [
            'first_name' => $orgDetails['admin_information'][0]['first_name'],
            'last_name'  => $orgDetails['admin_information'][0]['last_name'],
            'username'   => $orgDetails['admin_information'][0]['username'],
            'email'      => $orgDetails['admin_information'][0]['email'],
            'password'   => bcrypt($orgDetails['admin_information'][0]['password']),
            'role_id'    => 1,
            'org_id'     => $orgId
        ];

        return $adminData;
    }

    protected function makeSettingsData(array $orgDetails, $orgId)
    {
        $settingsData = [
            'default_field_values' => $orgDetails['default_field_values'],
            'default_field_groups' => $orgDetails['default_field_groups'],
            'organization_id'      => $orgId
        ];

        return $settingsData;
    }
}
