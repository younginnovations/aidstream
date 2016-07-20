<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class SettingsRequestManager
{
    public $requestHandler;

    function __construct(Version $version)
    {
        $this->requestHandler = $version->getSettingsElement()->getSettingsRequest();
    }
}
