<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class ActivityStatus
 * @package App\Services\RequestManager\Activity
 */
class ActivityStatus
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getActivityStatusRequest();

        return $this->req;
    }
}
