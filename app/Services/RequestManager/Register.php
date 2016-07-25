<?php namespace App\Services\RequestManager;

use App\Core\Version;

/**
 * Class Register
 * @package App\Services\RequestManager
 */
class Register
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getSettingsElement()->getRegisterRequest();
    }
}
