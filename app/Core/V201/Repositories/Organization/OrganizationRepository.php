<?php
namespace App\Core\V201\Repositories\Organization;

use App\Core\Repositories\OrganizationRepositoryInterface;
use App\Models\Organization\Organization;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @param $input
     */
    public function createOrganization(array $input)
    {
        $org = new Organization();
        $org->name = json_encode($input['name']);
        $org->identifier = $input['identifier'];
        $org->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrganizations()
    {
        return Organization::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOrganization($id)
    {
        return Organization::findorFail($id);
    }

    /**
     * @param $input
     * @param $org
     */
    public function updateOrganization($input, $org)
    {
        $org->name = json_encode($input['name']);
        $org->identifier = $input['identifier'];
        $org->save();
    }

}