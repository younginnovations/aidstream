<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class ContactInfo
 * @package App\Services\RequestManager\Activity
 */
class ContactInfo
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getContactInfoRequest();

        return $this->req;
    }
}
