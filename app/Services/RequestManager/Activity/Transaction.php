<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Transaction
 * @package App\Services\RequestManager\Activity
 */
class Transaction
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getTransactionRequest();
    }
}
