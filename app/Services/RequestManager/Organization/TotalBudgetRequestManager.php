<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class TotalBudgetRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getOrganizationElement()->getTotalBudgetRequest();
        return $this->req;
    }
}