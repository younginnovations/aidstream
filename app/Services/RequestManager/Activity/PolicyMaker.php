<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class PolicyMaker
 * @package App\Services\RequestManager\Activity
 */
class PolicyMaker
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getPolicyMakerRequest();
    }
}
