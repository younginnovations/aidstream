<?php namespace App\SuperAdmin\Repositories\SuperAdminInterfaces;

/**
 * Interface SuperAdmin
 * @package App\SuperAdmin\Repositories\SuperAdminInterfaces
 */
/**
 * Interface SuperAdmin
 * @package App\SuperAdmin\Repositories\SuperAdminInterfaces
 */
interface SuperAdmin
{
    /**
     * get all organization details
     * @return mixed
     */
    public function getOrganizations($organizationName = null, $version = null, $sysVersion = null);

    /**
     * get all Versions
     * @return mixed
     */
    public function getVersions();

    /**
     * get all system Versions
     * @return mixed
     */
    public function getSysVersions();

    /**
     * get organization details with specific id
     * @param $id
     * @return mixed
     */
    public function getOrganizationById($id);

    /**
     * get organization and related user details with specific id
     * @param $id
     * @return mixed
     */
    public function getOrganizationUserById($id);

    /**
     * update organization
     * @param       $orgId
     * @param array $orgDetails
     * @return mixed
     */
    public function saveOrganization(array $orgDetails, $orgId);

    /**
     * Returns organisation according their system version id
     *
     * @param $id
     * @return mixed
     */
    public function getOrganizationBySystemVersion($id);
}
