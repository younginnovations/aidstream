<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Sector
 * @package App\Services\RequestManager\Activity
 */
class Sector
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getSectorRequest();
    }
}
