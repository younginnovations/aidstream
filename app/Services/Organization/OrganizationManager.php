<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class OrganizationManager
{

    protected $repo;

    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getRepository();
    }


    public function createOrganization(array $input)
    {
        $this->repo->createOrganization($input);
    }

    public function getOrganizations()
    {
        return $this->repo->getOrganizations();
    }

    public function getOrganization($id)
    {
        return $this->repo->getOrganization($id);

    }

    public function updateOrganization($input, $organization)
    {
        $this->repo->updateOrganization($input, $organization);
    }

    public function updateStatus($input, $organization)
    {
        $this->repo->updateStatus($input, $organization);
    }


}