<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class UploadTransaction
 * @package App\Services\RequestManager\Activity
 */
class UploadTransaction
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getUploadTransactionRequest();
    }
}
