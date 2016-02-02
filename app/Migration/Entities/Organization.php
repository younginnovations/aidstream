<?php namespace App\Migration\Entities;


use App\Migration\MigrateOrganization;

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
     * @return array
     */
    public function getData()
    {
        $orgIds = ['2', '100', '9']; // get all organization ids;

        $organizationDetail = [];

        foreach ($orgIds as $id) {
            $organizationDetail[] = $this->organization->orgDataFetch($id);
        }

        return $organizationDetail;
    }
}
