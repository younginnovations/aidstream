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
    public function getOrganizationNameData($id)
    {
        return $this->repo->getOrganizationNameData($id);

    }
    /**
     * @param $organizationId
     * @param $input
     */
    public function create($organizationId, $input)
    {
        $this->repo->create($organizationId, $input);
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $this->repo->update($input, $organization);
    }


}