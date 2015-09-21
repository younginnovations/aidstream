<?php
namespace app\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;

class OrganizationManager
{
    protected $repo;

    /**
     * @param Version $version
     */
    public function __construct(Version $version, OrganizationData $orgData, OrganizationPublished $orgPublished)
    {
        $this->version = $version;
        $this->repo = $version->getOrganizationElement()->getRepository();
        $this->orgElement = $version->getOrganizationElement();
        $this->orgData = $orgData;
        $this->orgPublished = $orgPublished;
    }


    /**
     * @param array $input
     */
    public function createOrganization(array $input)
    {
        $this->repo->createOrganization($input);
    }

    /**
     * @return model
     */
    public function getOrganizations()
    {
        return $this->repo->getOrganizations();
    }

    /**
     * @param $id
     * @return model
     */
    public function getOrganization($id)
    {
        return $this->repo->getOrganization($id);
    }

    /**
     * @param array $input
     * @param Organization $organization
     */
    public function updateOrganization(array $input, Organization $organization)
    {
        $this->repo->updateOrganization($input, $organization);
    }

    /**
     * @param $id
     * @return model
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    /**
     * @param $input
     * @param $organizationData
     */
    public function getStatus($organization_id)
    {
        return $this->repo->getStatus($organization_id);
    }

    /**
     * @param $input
     * @param $organizationData
     */
    public function updateStatus($input, $id, $generateXml)
    {
        $this->repo->updateStatus($input, $id, $generateXml);
    }

    /**
     * @param $organization_id
     */
    public function resetStatus($organization_id)
    {
        $this->repo->resetStatus($organization_id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getPublishedFiles($id)
    {
        return $this->repo->getPublishedFiles($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePublishedFile($id)
    {
        return $this->repo->deletePublishedFile($id);
    }
}
