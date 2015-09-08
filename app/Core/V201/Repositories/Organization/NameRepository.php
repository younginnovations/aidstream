<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class NameRepository
{
    /**
     * @param $organization
     * @param $input
     */
    public function create($organization_id, $input)
    {
        $organization_id->name = json_encode($input['name']);
        $organization_id->save();
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $organization->name = json_encode($input['name']);
        $organization->save();
    }

    public function getOrganizationNameData($organization_id)
    {
        return OrganizationData::where('organization_id', $organization_id)->first();
    }

}