<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class CreateOrgReportingOrgRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        return $version->getOrganizationElement()->getCreateOrgReportingOrgRequest();
    }
}
