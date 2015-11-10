<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class CapitalSpend
 * @package App\Services\RequestManager\Activity
 */
class CapitalSpend
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getCapitalSpendRequest();
    }
}
