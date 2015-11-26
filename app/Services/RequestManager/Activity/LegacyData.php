<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class LegacyData
 * @package App\Services\RequestManager\Activity
 */
class LegacyData
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getLegacyDataRequest();
    }
}
