<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\Organization;

class OrgReportingOrgManager
{

    protected $repo;

    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getOrgReportingOrg()->getRepository();
    }

    /**
     * @param $organizationId
     * @param $input
     */
    public function create($organizationId, array $input)
    {
        $this->repo->create($organizationId, $input);
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update(array $input, Organization $organization)
    {
        $this->repo->update($input, $organization);
    }


}