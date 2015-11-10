<?php namespace App\SuperAdmin\Services;

use App\Models\Organization\Organization;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup as OrganizationGroupInterface;

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
     * @param OrganizationGroupInterface $organizationGroupInterface
     * @param UserGroup                  $userGroup
     * @param Organization               $organization
     */
    function __construct(OrganizationGroupInterface $organizationGroupInterface, UserGroup $userGroup, Organization $organization)
    {
        $this->organizationGroupInterface = $organizationGroupInterface;
        $this->userGroup                  = $userGroup;
        $this->organization               = $organization;
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

        return $this->organization->whereIn('id', $userGroup->assigned_organizations)->get();
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
}
