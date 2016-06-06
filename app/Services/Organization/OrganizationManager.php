<?php namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Models\Settings;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use App\Services\Twitter\TwitterAPI;

class OrganizationManager
{
    protected $repo;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @param Version               $version
     * @param Guard                 $auth
     * @param OrganizationData      $orgData
     * @param OrganizationPublished $orgPublished
     * @param Logger                $logger
     * @param TwitterAPI            $twitter
     */
    public function __construct(Version $version, Guard $auth, OrganizationData $orgData, OrganizationPublished $orgPublished, Logger $logger, TwitterAPI $twitter)
    {
        $this->version      = $version;
        $this->repo         = $version->getOrganizationElement()->getRepository();
        $this->orgElement   = $version->getOrganizationElement();
        $this->orgData      = $orgData;
        $this->orgPublished = $orgPublished;
        $this->logger       = $logger;
        $this->auth         = $auth;
        $this->twitterApi   = $twitter;
    }

    /**
     * @return mixed
     */
    public function getOrganizationElement()
    {
        return $this->orgElement;
    }

    /**
     * @param array $input
     */
    public function createOrganization(array $input)
    {
        $this->repo->createOrganization($input);
    }

    /**
     * @param $select
     * @return model
     */
    public function getOrganizations($select = '*')
    {
        return $this->repo->getOrganizations($select);
    }

    /**
     * @param $id
     * @return Organization
     */
    public function getOrganization($id)
    {
        return $this->repo->getOrganization($id);
    }

    /**
     * @param array        $input
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
     * @param $organization_id
     * @return
     */
    public function getStatus($organization_id)
    {
        return $this->repo->getStatus($organization_id);
    }

    /**
     * @param array            $input
     * @param OrganizationData $organizationData
     */
    public function updateStatus(array $input, OrganizationData $organizationData)
    {
        $result = $this->repo->updateStatus($input, $organizationData);
        if ($result) {
            $organizationWorkflow = $input['status'];
            $statusLabel          = ['Completed', 'Verified', 'Published'];
            $status               = $statusLabel[$organizationWorkflow - 1];
            $this->logger->info(sprintf('Organization has been %s', $status));
            $this->logger->activity(
                "organization.organization_status_changed",
                [
                    'name'   => $this->auth->user()->organization->name,
                    'status' => $status
                ]
            );
        }

        return $result;
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

    /**
     * @param $id
     * @return mixed
     */
    public function updatePublishToRegister($id)
    {
        return $this->repo->updatePublishToRegister($id);
    }

    /**
     * @param Organization $organization
     * @param Settings     $settings
     * @param              $filename
     * @return mixed
     */
    public function publishToRegistry(Organization $organization, Settings $settings, $filename)
    {
        $response = $this->repo->publishToRegistry($organization, $settings, $filename);

        if ($response) {
            $twitter = $this->twitterApi->post($settings, $organization);
        }

        return $response;
    }

    public function saveOrganizationPublishedFiles($filename, $orgId)
    {
        return $this->repo->saveOrganizationPublishedFiles($filename, $orgId);
    }

    /**
     * Returns an array of Organization's users' Ids.
     * @param $id
     * @return array
     */
    public function getOrganizationUsers($id)
    {
        $users   = $this->repo->getOrganization($id)->users;
        $userIds = [];

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }

        return $userIds;
    }

    /**
     * check if reporting organization already exists while saving settings
     * @param $reportOrg
     * @return mixed
     */
    public function checkReportingOrganization($reportOrg)
    {
        return $this->repo->getReportingOrganizations($reportOrg);
    }

    /**
     * @param $id
     * @return App\Tz\Aidstream\Models\Organization
     */
    public function getTanzanianOrganization($id)
    {
        return app()->make(App\Tz\Aidstream\Models\Organization::class)->findOrFail($id);
    }
}
