<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class Result
 * @package App\Services\RequestManager\Activity
 */
class Result
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getResultRequest();
    }
}
