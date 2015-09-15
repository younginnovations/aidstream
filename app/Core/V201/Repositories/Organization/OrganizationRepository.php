<?php
namespace app\Core\V201\Repositories\Organization;

use App\Core\Repositories\OrganizationRepositoryInterface;
use App\Models\Organization\Organization;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var Organization
     */
    private $org;

    /**
     * @param Organization $org
     */
    function __construct(Organization $org)
    {
        $this->org = $org;
    }

    /**
     * @param $input
     */
    public function createOrganization(array $input)
    {
        $org                  = new Organization();
        $org->name            = json_encode($input['name']);
        $org->user_identifier = $input['user_identifier'];
        $org->address         = $input['address'];
        $org->telephone       = $input['telephone'];
        $org->reporting_org   = json_encode($input['reporting_org']);
        $org->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrganizations()
    {
        return $this->org->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOrganization($id)
    {
        return $this->org->findorFail($id);
    }

    /**
     * @param $input
     * @param $org
     */
    public function updateOrganization($input, $org)
    {
        $org->name            = $input['name'];
        $org->user_identifier = $input['user_identifier'];
        $org->address         = $input['address'];
        $org->telephone       = $input['telephone'];
        $org->reporting_org   = json_encode($input['reporting_org']);
        $org->status          = $input['status'];
        $org->save();
    }

    /**
     * @param $input
     * @param $org
     */
    public function updateStatus($input, $org)
    {
        $org->status = $input['status'];
        $org->save();
    }
}
