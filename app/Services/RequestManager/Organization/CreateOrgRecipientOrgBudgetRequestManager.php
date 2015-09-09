<?php
namespace App\Services\RequestManager\Organization;

use App\Core\Version;
use App;

class CreateOrgRecipientOrgBudgetRequestManager
{

    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getOrganizationElement()->getRecipientOrgBudgetRequest();
        return $this->req;
    }
}
