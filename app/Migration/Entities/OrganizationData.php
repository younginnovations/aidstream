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
     * @var MigrateOrganizationData
     */
    protected $organizationData;
    /**
     * @var OrganizationDataQuery
     */
    protected $organizationDataQuery;

    /**
     * OrganizationData constructor.
     * @param MigrateOrganizationData $organizationData
     */
    public function __construct(MigrateOrganizationData $organizationData, OrganizationDataQuery $organizationDataQuery)
    {
        $this->organizationData      = $organizationData;
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

//        foreach ($accountIds as $accountId) {
//            $organization = getOrganizationFor($accountId);
//
//            if ($organization) {
//                $this->data[] = $this->organizationData->OrganizationDataFetch($organization->id, $accountId);
//            }
//        }
//
//        return $this->data;
    }
}
