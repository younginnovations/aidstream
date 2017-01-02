<?php namespace App\SuperAdmin\Services;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Organization\Organization;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup as OrganizationGroupInterface;
use Exception;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

/**
 * Class OrganizationGroupManager
 * @package App\SuperAdmin\Services
 */
class OrganizationGroupManager
{
    /**
     * @var OrganizationGroupInterface
     */
    protected $organizationGroupInterface;
    /**
     * @var UserGroup
     */
    protected $userGroup;
    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;
    /**
     * @var OrganizationRepository
     */
    private $organizationRepository;

    /**
     * @param OrganizationGroupInterface $organizationGroupInterface
     * @param UserGroup                  $userGroup
     * @param Organization               $organization
     * @param DatabaseManager            $databaseManager
     * @param LoggerInterface            $logger
     * @param OrganizationRepository     $organizationRepository
     */
    function __construct(
        OrganizationGroupInterface $organizationGroupInterface,
        UserGroup $userGroup,
        Organization $organization,
        DatabaseManager $databaseManager,
        LoggerInterface $logger,
        OrganizationRepository $organizationRepository
    ) {
        $this->organizationGroupInterface = $organizationGroupInterface;
        $this->userGroup                  = $userGroup;
        $this->organization               = $organization;
        $this->databaseManager            = $databaseManager;
        $this->logger                     = $logger;
        $this->organizationRepository     = $organizationRepository;
    }

    /**
     * return all grouped organizations
     * @return mixed
     */
    public function getOrganizationGroups()
    {
        return $this->organizationGroupInterface->getOrganizationGroups();
    }

    /**
     * get all organization user groups by id
     * @param $id
     * @return array
     */
    public function getOrganizationGroupUserById($id)
    {
        return $this->organizationGroupInterface->getOrganizationGroupUserById($id);
    }

    /**
     * saves organization group
     * @param array $groupDetails
     * @param null  $id
     * @return mixed
     */
    public function save(array $groupDetails, $id = null)
    {
        return $this->organizationGroupInterface->save($groupDetails, $id);
    }

    /**
     * get groups by user Id
     * @param $userId
     * @return mixed
     */
    public function getGroupsByUserId($userId)
    {
        $userGroup = $this->userGroup->whereUserId($userId)->first();

        return $this->organization->whereIn('id', $userGroup->assigned_organizations)->with(['settings'])->orderBy('name', 'asc')->get();
    }

    /**
     * get the model for group update
     * @param $groupId
     * @return mixed
     */
    public function getModelForUpdate($groupId)
    {
        $orgGroupInfo                                        = $this->getOrganizationGroupUserById($groupId);
        $model['new_organization_group'][0]                  = $orgGroupInfo;
        $model['new_organization_group'][0]['organizations'] = $orgGroupInfo['assigned_organizations'];
        $model['group_admin_information'][0]                 = $orgGroupInfo['user'];

        return $model;
    }

    /**
     * Delete an existing UserGroup.
     * @param $userGroup
     * @return bool
     */
    public function deleteGroup($userGroup)
    {
        try {
            $userGroupName = $userGroup->group_name;
            $groupAdmin    = $userGroup->user;
            $this->databaseManager->beginTransaction();
            $userGroup->delete();
            $groupAdmin->delete();

            $this->databaseManager->commit();

            $this->logger->info(
                "User Group successfully deleted.",
                [
                    'group_name'  => $userGroupName,
                    'super_admin' => auth()->user()->username,
                ]
            );

            $this->logger->activity(
                "activity.group_organization_deleted",
                [
                    'group_name'  => $userGroupName,
                    'super_admin' => auth()->user()->username,
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();

            $this->logger->error(
                sprintf("User Group could not be deleted due to %s.", $exception->getMessage()),
                [
                    'group_name'  => $userGroupName,
                    'super_admin' => auth()->user()->username,
                    'trace'       => $exception->getTraceAsString()
                ]
            );
        }

    }

    /**
     * Updates system version of an organisation
     *
     * @param $orgId
     * @param $system_version
     * @return mixed
     */
    public function updateSystemVersion($orgId, $system_version)
    {
        return $this->organizationRepository->updateSystemVersion($orgId, $system_version);
    }
}
