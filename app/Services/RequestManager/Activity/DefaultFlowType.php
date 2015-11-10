<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class DefaultFlowType
 * @package App\Services\RequestManager\Activity
 */
class DefaultFlowType
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getDefaultFlowTypeRequest();
    }
}
