<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class OtherIdentifierRequestManager
 * @package App\Services\RequestManager\Activity
 */
class OtherIdentifierRequestManager
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getOtherIdentifierRequest();

        return $this->req;
    }
}

