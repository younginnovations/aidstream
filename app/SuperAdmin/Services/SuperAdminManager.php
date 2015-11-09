<?php namespace App\SuperAdmin\Services;

use App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin as SuperAdminInterface;

/**
 * Class SuperAdminManager
 * @package App\SuperAdmin\Services
 */
class SuperAdminManager
{
    /**
     * @var SuperAdminInterface
     */
    protected $adminInterface;

    /**
     * @param SuperAdminInterface $adminInterface
     */
    function __construct(SuperAdminInterface $adminInterface)
    {
        $this->adminInterface = $adminInterface;
    }

    /**
     * return all organizations
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->adminInterface->getOrganizations();
    }

    /**
     * get organization by its id
     * @param $id
     * @return mixed
     */
    public function getOrganizationById($id)
    {
        return $this->adminInterface->getOrganizationById($id);
    }

    /**
     * get organization with user information
     * @param $id
     * @return mixed
     */
    public function getOrganizationUserById($id)
    {
        return $this->adminInterface->getOrganizationUserById($id);
    }

    /**
     * saves organization
     * @param null  $id
     * @param array $orgDetails
     * @return mixed
     */
    public function saveOrganization(array $orgDetails, $id = null)
    {
        return $this->adminInterface->saveOrganization($orgDetails, $id);
    }
}
