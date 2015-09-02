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
        $org->user_identifier = $input['user_identifier'];
        $org->address = $input['address'];
        $org->telephone = $input['telephone'];
        $org->reporting_org = json_encode($input['reporting_org']);
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
        $org->user_identifier = $input['user_identifier'];
        $org->address = $input['address'];
        $org->telephone = $input['telephone'];
        $org->reporting_org = json_encode($input['reporting_org']);
        $org->save();
    }

}