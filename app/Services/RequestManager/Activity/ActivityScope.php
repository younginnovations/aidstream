<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class ActivityScope
 * @package App\Services\RequestManager\Activity
 */
class ActivityScope
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getActivityScopeRequest();
    }
}
