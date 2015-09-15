<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class RecipientCountryBudgetRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getOrganizationElement()->getRecipientCountryBudgetRequest();

        return $this->req;
    }
}