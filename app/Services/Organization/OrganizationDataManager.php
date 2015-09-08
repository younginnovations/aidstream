<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class OrganizationDataManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getName()->getRepository();
    }


    public function createOrganizationData(array $input)
    {
        $this->repo->createOrganization($input);
    }

    public function getOrganizationsData()
    {
        return  $this->repo->getOrganizations();
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganization($id);

    }

    public function updateOrganizationData($input, $organization)
    {
        $this->repo->updateOrganization($input, $organization);
    }



}