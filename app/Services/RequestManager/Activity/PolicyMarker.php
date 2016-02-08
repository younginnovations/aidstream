<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class PolicyMarker
 * @package App\Services\RequestManager\Activity
 */
class PolicyMarker
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getPolicyMarkerRequest();
    }
}
