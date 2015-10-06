<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class RecipientRegion
 * @package App\Services\RequestManager\Activity
 */
class RecipientRegion
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getRecipientRegionRequest();

        return $this->req;
    }
}
