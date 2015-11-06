<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Budget
 * @package App\Services\RequestManager\Activity
 */
class Budget
{
    protected $req;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getBudgetRequest();
    }
}
