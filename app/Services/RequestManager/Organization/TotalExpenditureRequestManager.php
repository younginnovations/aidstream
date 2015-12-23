<?php namespace App\Services\RequestManager\Organization;

use App\Core\Version;

/**
 * Class TotalExpenditureRequestManager
 * @package App\Services\RequestManager\Organization
 */
class TotalExpenditureRequestManager
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getOrganizationElement()->getTotalExpenditureRequest();
    }
}
