<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

class IatiIdentifierRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getIatiIdentifierRequest();

        return $this->req;
    }
}