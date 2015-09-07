<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class NameRepository
{
    /**
     * @param $organization
     * @param $input
     */
    public function create($organization, $input)
    {
        $organization->name = json_encode($input['name']);
        $organization->save();
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

    /**
     * @param $id
     * @return mixed
     */
    public function getOrganizationData($id)
    {
        return OrganizationData::where('organization_id', $org_id)->first();
    }
}