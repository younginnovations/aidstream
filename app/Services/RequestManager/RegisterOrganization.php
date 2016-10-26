<?php namespace App\Services\RequestManager;

use App\Core\Version;

/**
 * Class RegisterOrganization
 * @package App\Services\RequestManager
 */
class RegisterOrganization
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getSettingsElement()->getRegisterOrganizationRequest();
    }
}
