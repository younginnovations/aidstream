<?php namespace App\SuperAdmin\Repositories\SuperAdminInterfaces;

/**
 * Interface OrganizationGroup
 * @package App\SuperAdmin\Repositories\SuperAdminInterfaces
 */
interface OrganizationGroup
{
    /**
     * get all grouped organizations
     * @return mixed
     */
    public function getOrganizationGroups();

    /**
     * get all organization user groups by id
     * @param $id
     * @return mixed
     */
    public function getOrganizationGroupUserById($id);

    /**
     * save organization group information
     * @param array $groupDetails
     * @param       $id
     * @return mixed
     */
    public function save(array $groupDetails, $id);
}
