<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class DefaultAidType
 * @package App\Services\RequestManager\Activity
 */
class DefaultAidType
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getDefaultAidTypeRequest();
    }
}
