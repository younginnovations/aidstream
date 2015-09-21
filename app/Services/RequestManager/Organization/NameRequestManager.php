<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class NameRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getOrganizationElement()->getNameRequest();

        return $this->req;
    }
}