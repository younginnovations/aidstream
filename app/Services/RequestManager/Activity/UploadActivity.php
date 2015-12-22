<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class UploadActivity
 * @package App\Services\RequestManager\Activity
 */
class UploadActivity
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getUploadActivityRequest();
    }
}
