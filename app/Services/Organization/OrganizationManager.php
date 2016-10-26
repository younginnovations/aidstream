<?php namespace App\Services\Organization;

use App\Core\V201\Repositories\UserRepository;
use App\Core\Version;
use App;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Models\Settings;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use App\Services\Twitter\TwitterAPI;
use Illuminate\Contracts\Logging\Log;
use Kris\LaravelFormBuilder\FormBuilder;

class OrganizationManager
{
    /**
     * @var
     */
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
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @param Version               $version
     * @param Guard                 $auth
     * @param OrganizationData      $orgData
     * @param OrganizationPublished $orgPublished
     * @param Logger                $logger
     * @param Log                   $dbLogger
     * @param TwitterAPI            $twitter
     * @param FormBuilder           $formBuilder
     */
    public function __construct(
        Version $version,
        Guard $auth,
        OrganizationData $orgData,
        OrganizationPublished $orgPublished,
        UserRepository $userRepo,
        Logger $logger,
        Log $dbLogger,
        TwitterAPI $twitter,
        FormBuilder $formBuilder
    ) {
        $this->version      = $version;
        $this->repo         = $version->getOrganizationElement()->getRepository();
        $this->orgElement   = $version->getOrganizationElement();
        $this->orgData      = $orgData;
        $this->orgPublished = $orgPublished;
        $this->logger       = $logger;
        $this->auth         = $auth;
        $this->twitterApi   = $twitter;
        $this->formBuilder  = $formBuilder;
        $this->dbLogger     = $dbLogger;
        $this->userRepo     = $userRepo;
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

    /** display form to view organization information
     * @param $formOptions
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function viewOrganizationInformation($formOptions)
    {
        return $this->formBuilder->create('App\Core\V201\Forms\Settings\OrganizationInformation', $formOptions);

    }

    /** save organization information
     * @param $organizationInfo
     * @param $organization
     * @return bool
     */
    public function saveOrganizationInformation($organizationInfo, $organization)
    {
        try {
            $old_user_identifier = strtolower($organization->user_identifier);
            $new_user_identifier = strtolower($organizationInfo['user_identifier']);

            ($old_user_identifier === $new_user_identifier) ? $check = true : $check = false;
            $response = true;

            if (!$check) {
                $status   = $this->updateUsername($old_user_identifier, $new_user_identifier);
                $response = "Username updated";
            }
            $result = $this->repo->saveOrganizationInformation($organizationInfo, $organization);

            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return $response;
        } catch (Exception $e) {
            $this->logger->error($e, ['settings' => $organizationInfo]);
        }

        $response = false;

        return $response;
    }

    /** update username of all users of the organization.
     * @param $old_user_identifier
     * @param $new_user_identifier
     */
    public function updateUsername($old_user_identifier, $new_user_identifier)
    {
        return $this->userRepo->updateUsername($old_user_identifier, $new_user_identifier);
    }

    /**
     * deletes element which has been clicked.
     * @param $organization
     * @param $element
     * @return bool
     */
    public function deleteElement($organization, $element)
    {
        try {
            $this->repo->deleteELement($organization, $element);
            $this->logger->info(
                sprintf('Organization element %s has been deleted.', $element),
                ['for ' => $organization->id]
            );
            $this->logger->activity(
                "organization.organization_element_deleted",
                [
                    'element'         => $element,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return true;

        } catch (Exception $exception) {
            $this->logger->error($exception);
        }

        return false;
    }

    /**
     * change the organization data status to draft.
     * @param $organization
     * @return mixed
     */
    public function resetOrganizationWorkflow($organization)
    {
        return $this->repo->resetOrganizationWorkflow($organization);
    }

    /** Returns published data of organization
     * @param $organization_id
     * @return mixed
     */
    public function getPublishedOrganizationData($organization_id)
    {
        return $this->repo->getPublishedOrganizationData($organization_id);
    }
}
