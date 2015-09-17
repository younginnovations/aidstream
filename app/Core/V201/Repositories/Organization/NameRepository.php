<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class NameRepository
{
    /**
     * @var OrganizationData
     */
    private $org;

    /**
     * @param OrganizationData $org
     */
    function __construct(OrganizationData $org)
    {
        $this->org = $org;
    }

    /**
     * @param $input
     * @param $organizationData
     * @return bool
     */
    public function update(array $input, OrganizationData $organizationData)
    {
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
        return $this->org->where('organization_id', $organization_id)->first();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationNameData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->name;
    }

    public function getStatus($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->status;
    }

    public function updateStatus($input, $organizationData)
    {
        $organizationData->status = $input['status'];
        $organizationData->save();
    }

    public function resetStatus($organization_id)
    {
        $this->org->where('organization_id', $organization_id)->update(['status' => 0]);
    }

}