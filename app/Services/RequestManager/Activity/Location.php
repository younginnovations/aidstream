<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class Location
 * @package App\Services\RequestManager\Activity
 */
class Location
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getLocationRequest();
    }
}
