<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class SettingsRequestManager
{
    protected $req;

    function __construct(Version $version)
    {
        $this->req = $version->getSettingsElement()->getSettingsRequest();

        return $this->req;
    }
}
