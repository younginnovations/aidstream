<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class HumanitarianScope
 * @package App\Services\RequestManager\Activity
 */
class HumanitarianScope
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getHumanitarianScopeRequest();
    }
}
