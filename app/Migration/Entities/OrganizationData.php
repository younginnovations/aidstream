<?php namespace App\Migration\Entities;


use App\Migration\MigrateOrganizationData;
use App\Migration\Migrator\Data\OrganizationDataQuery;

/**
 * Class OrganizationData
 * @package App\Migration\Entities
 */
class OrganizationData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var OrganizationDataQuery
     */
    protected $organizationDataQuery;

    /**
     * OrganizationData constructor.
     * @param OrganizationDataQuery   $organizationDataQuery
     */
    public function __construct(OrganizationDataQuery $organizationDataQuery)
    {
        $this->organizationDataQuery = $organizationDataQuery;
    }

    /**
     * Gets OrganizationData data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->organizationDataQuery->executeFor($accountIds);
    }
}
