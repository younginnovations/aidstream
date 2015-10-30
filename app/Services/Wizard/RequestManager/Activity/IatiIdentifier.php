<?php namespace App\Services\Wizard\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class IatiIdentifier
 * @package App\Services\Wizard\RequestManager\Activity
 */
class IatiIdentifier
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getWizardIatiIdentifierRequest();
    }
}
