<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class DefaultFinanceType
 * @package App\Services\RequestManager\Activity
 */
class DefaultFinanceType
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getDefaultFinanceTypeRequest();
    }
}
