<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

class RelatedActivity
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getRelatedActivityRequest();
    }
}
