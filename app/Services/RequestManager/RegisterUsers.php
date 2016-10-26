<?php namespace App\Services\RequestManager;

use App\Core\Version;

/**
 * Class RegisterUsers
 * @package App\Services\RequestManager
 */
class RegisterUsers
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getSettingsElement()->getRegisterUsersRequest();
    }
}
