<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;

class RecipientRegionBudget
{
    function __construct(Version $version)
    {
        return $version->getOrganizationElement()->getRecipientRegionBudgetRequest();
    }
}
