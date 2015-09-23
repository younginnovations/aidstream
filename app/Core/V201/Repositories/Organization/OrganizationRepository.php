<?php
namespace app\Core\V201\Repositories\Organization;

use App\Core\Repositories\OrganizationRepositoryInterface;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var Organization
     */
    private $org;

    /**
     * @param Organization $org
     */
    function __construct(Organization $org, OrganizationData $orgData)
    {
        $this->org = $org;
        $this->orgData = $orgData;
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
     * @return model
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
     * @param $id
     * @return model
     */
    public function getOrganizationData($id)
    {
        return $this->orgData->findorFail($id);
    }

    /**
     * @param $organization_id
     * @return model
     */
    public function getStatus($organization_id)
    {
        return $this->orgData->where('organization_id', $organization_id)->first()->status;
    }

    /**
     * @param $input
     * @param $id
     * @param $generateXml
     * @return mixed
     */
    public function updateStatus($input, $id, $generateXml)
    {
        $organizationData = $this->getOrganizationData($id);
        $status = $input['status'];
        if($status == 1) {
            $organization = $this->getOrganization($id);
            if(!isset($organization->reporting_org) || !isset($organizationData->name))
                return redirect()->back()->withMessage('Organization data is not Complete.');
        } else if($status == 3) {
            $generateXml->generate($id);
        }
        $organizationData->status = $status;
        $organizationData->save();
    }

    /**
     * @param $organization_id
     */
    public function resetStatus($organization_id)
    {
        $this->orgData->where('organization_id', $organization_id)->update(['status' => 0]);
    }

    /**
     * @param $id
     * @return model
     */
    public function getPublishedFiles($id)
    {
        return $this->orgPublished->where('organization_id', $id)->get();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePublishedFile($id)
    {
        $result = $this->orgPublished->find($id);
        if($result) {
            $file = public_path('uploads/files/organization/' .$result->filename);
            $result = $result->delete();
            if($result && file_exists($file)) unlink($file);
        }
        return $result;
    }

}
