<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;
Use App;

class SettingsRequestManager
{

    function __construct(Version $version)
    {
        return $version->getSettingsElement()->getSettingsRequest();
    }
}
