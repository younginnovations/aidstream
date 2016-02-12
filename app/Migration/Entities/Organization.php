<?php namespace App\Migration\Entities;


use App\Migration\MigrateOrganization;
use App\Migration\Migrator\Data\OrganizationQuery;

/**
 * Class Organization
 * @package App\Migration\Entities
 */
class Organization
{
    /**
     * @var MigrateOrganization
     */
    protected $organization;

    /**
     * Organization constructor.
     * @param MigrateOrganization $organization
     */
    public function __construct(MigrateOrganization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Gets Organizations data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        $organizationQuery = new OrganizationQuery();

        return $organizationQuery->executeFor($accountIds);
   }
}
