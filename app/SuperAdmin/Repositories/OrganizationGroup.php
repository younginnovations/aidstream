<?php namespace App\SuperAdmin\Repositories;

use App\Models\Organization\Organization;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup as OrganizationGroupInterface;
use App\User;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\DatabaseManager;
use Auth;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class OrganizationGroup
 * @package App\SuperAdmin\Repositories
 */
class OrganizationGroup implements OrganizationGroupInterface
{
    /**
     * @var UserGroup
     */
    protected $userGroup;
    protected $organization;
    protected $database;
    protected $logger;
    protected $user;
    protected $dbLogger;

    /**
     * @param User            $user
     * @param UserGroup       $userGroup
     * @param Organization    $organization
     * @param DatabaseManager $database
     * @param Logger          $logger
     * @param DbLogger        $dbLogger
     */
    public function __construct(
        User $user,
        UserGroup $userGroup,
        Organization $organization,
        DatabaseManager $database,
        Logger $logger,
        DbLogger $dbLogger
    ) {
        $this->organization = $organization;
        $this->database     = $database;
        $this->logger       = $logger;
        $this->user         = $user;
        $this->dbLogger     = $dbLogger;
        $this->userGroup    = $userGroup;
    }

    /**
     * get all group organizations
     * @return mixed
     */
    public function getOrganizationGroups()
    {
        return $this->userGroup->all();
    }

    /**
     * get all organization user groups by id
     * @param $id
     * @return array
     */
    public function getOrganizationGroupUserById($id)
    {
        $userId = $this->userGroup->findOrFail($id)->user_id;

        return $this->userGroup->with('user')->whereUserId($userId)->first()->toArray();
    }

    public function save(array $groupDetails, $id = null)
    {
        try {
            $this->database->beginTransaction();
            $userId    = ($id) ? $this->userGroup->findOrFail($id)->user_id : null;
            $adminData = $this->putUserToGroup($groupDetails);
            $user      = $this->user->firstOrNew(['id' => $userId]);
            $user->fill($adminData)->save();

            $groupData = $this->MakeOrganizationGroup($groupDetails, $user->id);
            $group     = $this->userGroup->firstOrNew(['user_id' => $user->id]);
            $group->fill($groupData)->save();

            $this->database->commit();

            $this->logger->info(($id) ? 'Group information Updated' : 'Organization Group added');
            $this->dbLogger->activity(
                ($id) ? "group_updated" : "group_added",
                [
                    'group_id' => $group->id,
                    'user_id'  => $id
                ]
            );
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['settings' => $groupDetails]);
        }
    }

    /**
     * put user to group
     * @param array $groupDetails
     * @return array
     */
    protected function putUserToGroup(array $groupDetails)
    {
        $adminData = [
            'first_name' => $groupDetails['group_admin_information'][0]['first_name'],
            'last_name'  => $groupDetails['group_admin_information'][0]['last_name'],
            'username'   => $groupDetails['group_admin_information'][0]['username'],
            'email'      => $groupDetails['group_admin_information'][0]['email'],
            'password'   => bcrypt($groupDetails['group_admin_information'][0]['password']),
            'role_id'    => 4
        ];

        return $adminData;
    }

    /**
     * make organization group
     * @param array $groupDetails
     * @param       $userId
     * @return array
     */
    protected function MakeOrganizationGroup(array $groupDetails, $userId)
    {
        $groupData = [
            'group_name'             => $groupDetails['new_organization_group'][0]['group_name'],
            'assigned_organizations' => $groupDetails['new_organization_group'][0]['organizations'],
            'group_identifier'       => $groupDetails['new_organization_group'][0]['group_identifier'],
            'user_id'                => $userId
        ];

        return $groupData;
    }
}
