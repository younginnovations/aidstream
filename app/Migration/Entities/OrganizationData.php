<?php namespace App\Migration\Entities;


use App\Migration\MigrateOrganizationData;

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
     * OrganizationData constructor.
     * @param MigrateOrganizationData $organizationData
     */
    public function __construct(MigrateOrganizationData $organizationData)
    {
        $this->organizationData = $organizationData;
    }

    /**
     * Gets OrganizationData data from old database.
     * @return array
     */
    public function getData()
    {
        $orgIds = ['2', '9', '100'];

        foreach ($orgIds as $id) {
            $this->data[] = $this->organizationData->OrganizationDataFetch($id);
        }

        return $this->data;
    }
}
