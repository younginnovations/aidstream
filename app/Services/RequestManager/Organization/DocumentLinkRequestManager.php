<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class DocumentLinkRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getOrganizationElement()->getDocumentLinkRequest();
        return $this->req;
    }
}