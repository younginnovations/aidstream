<?php namespace App\Services\RequestManager;

use App\Core\Version;

/**
 * Class Password
 * @package App\Services\RequestManager
 */
class Password
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getSettingsElement()->getPasswordRequest();
    }
}
