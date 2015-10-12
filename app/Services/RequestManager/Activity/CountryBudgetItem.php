<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class CountryBudgetItem
 * @package App\Services\RequestManager\Activity
 */
class CountryBudgetItem
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getCountryBudgetItemRequest();
    }
}
