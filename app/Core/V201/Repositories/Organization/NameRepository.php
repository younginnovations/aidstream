<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class NameRepository
{
    /**
     * @var OrganizationData
     */
    private $orgData;

    /**
     * @param OrganizationData $org
     */
    function __construct(OrganizationData $orgData)
    {
        $this->orgData = $orgData;
    }

    /**
     * @param $input
     * @param $organizationData
     * @return bool
     */
    public function update(array $input, OrganizationData $organizationData)
    {
        unset($input['_token']);
        unset($input['_method']);
        $organizationData->name = $input['name'];

        return $organizationData->save();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationData($organization_id)
    {
        return $this->orgData->where('organization_id', $organization_id)->first();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationNameData($organization_id)
    {
        return $this->orgData->where('organization_id', $organization_id)->first()->name;
    }
}
