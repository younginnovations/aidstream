<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Description
 * @package App\Services\RequestManager\Activity
 */
class Description
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getDescriptionRequest();

        return $this->req;
    }
}
