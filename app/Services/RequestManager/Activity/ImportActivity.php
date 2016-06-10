<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class ImportActivity
 * @package App\Services\RequestManager\Activity
 */
class ImportActivity
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getImportActivityRequest();
    }
}
