<?php
namespace app\Core\V201\Repositories\Organization;

use App\Core\Repositories\OrganizationRepositoryInterface;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var Organization
     */
    private $org;

    /**
     * @param Organization          $org
     * @param OrganizationData      $orgData
     * @param OrganizationPublished $orgPublished
     */
    function __construct(Organization $org, OrganizationData $orgData, OrganizationPublished $orgPublished)
    {
        $this->org          = $org;
        $this->orgData      = $orgData;
        $this->orgPublished = $orgPublished;
    }

    /**
     * write brief description
     * @param array $input
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
        return $this->orgData->where('organization_id', $id)->first();
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
     * @param array            $input
     * @param OrganizationData $organizationData
     */
    public function updateStatus(array $input, OrganizationData $organizationData)
    {
        $organizationData->status = $input['status'];
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
        if ($result) {
            $file   = public_path('uploads/files/organization/' . $result->filename);
            $result = $result->delete();
            if ($result && file_exists($file)) {
                unlink($file);
            }
        }

        return $result;
    }
}
