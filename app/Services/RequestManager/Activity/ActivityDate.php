<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

class ActivityDate
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getActivityDateRequest();

        return $this->req;
    }
}
