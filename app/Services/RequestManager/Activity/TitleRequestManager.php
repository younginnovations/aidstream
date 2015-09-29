<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class TitleRequestManager
 * @package App\Services\RequestManager\Activity
 */
class TitleRequestManager
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getTitleRequest();

        return $this->req;
    }
}

