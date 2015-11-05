<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class CollaborationType
 * @package App\Services\RequestManager\Activity
 */
class CollaborationType
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getCollaborationTypeRequest();
    }
}
