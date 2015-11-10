<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class DefaultTiedStatus
 * @package App\Services\RequestManager\Activity
 */
class DefaultTiedStatus
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getDefaultTiedStatusRequest();
    }
}
