<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class RecipientCountry
 * @package App\Services\RequestManager\Activity
 */
class RecipientCountry
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getRecipientCountryRequest();
    }
}
