<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class ParticipatingOrganization
 * @package App\Services\RequestManager\Activity
 */
class ParticipatingOrganization
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->req = $version->getActivityElement()->getParticipatingOrganizationRequest();

        return $this->req;
    }
}
