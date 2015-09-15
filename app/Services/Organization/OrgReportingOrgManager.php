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
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $this->repo->update($input, $organization);
    }


}