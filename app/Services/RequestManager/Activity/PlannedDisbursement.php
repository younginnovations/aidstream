<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class PlannedDisbursement
 * @package App\Services\RequestManager\Activity
 */
class PlannedDisbursement
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getPlannedDisbursementRequest();
    }
}
