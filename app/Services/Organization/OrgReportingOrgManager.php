<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

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