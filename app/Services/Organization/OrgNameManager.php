<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class OrgNameManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getName()->getRepository();
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);

    }

    public function getOrganizationNameData($id)
    {
        return $this->repo->getOrganizationNameData($id);

    }
    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organizationData)
    {
        $this->repo->update($input, $organizationData);
    }


}