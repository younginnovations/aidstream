<?php namespace App\Services\Organization;

use App;
use App\Core\V201\Repositories\UserRepository;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Models\Settings;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Logging\Log as Logger;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class OrganizationManager
 * @package App\Services\Organization
 */
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
     * @param UserRepository        $userRepo
     * @param Logger                $logger
     * @param Log                   $dbLogger
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
        FormBuilder $formBuilder
    ) {
        $this->version      = $version;
        $this->repo         = $version->getOrganizationElement()->getRepository();
        $this->orgElement   = $version->getOrganizationElement();
        $this->orgData      = $orgData;
        $this->orgPublished = $orgPublished;
        $this->logger       = $logger;
        $this->auth         = $auth;
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
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    /**
     * Find OrganizationData.
     *
     * @param $id
     * @return OrganizationData
     */
    public function findOrganizationData($id)
    {
        return $this->repo->findOrganizationData($id);
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

//        if ($response) {
//            $twitter = $this->twitterApi->post($settings, $organization);
//        }

        return $response;
    }

    /**
     * @param $filename
     * @param $orgId
     * @return mixed
     */
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
            $oldUserIdentifier = strtolower($organization->user_identifier);
            $newUserIdentifier = strtolower(getVal($organizationInfo, ['user_identifier']));

            $isSameIdentifier = ($oldUserIdentifier === $newUserIdentifier) ? true : false;
            $response         = true;

            if (!$isSameIdentifier) {
                $status   = $this->updateUsername($oldUserIdentifier, $newUserIdentifier);
                $response = 'Username updated';
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

    /**
     * Update username of all users of the organization.
     * @param $oldUserIdentifier
     * @param $newUserIdentifier
     * @return bool
     */
    public function updateUsername($oldUserIdentifier, $newUserIdentifier)
    {
        try {
            $this->userRepo->updateUsername($oldUserIdentifier, $newUserIdentifier);
            $this->logger->info(
                'Username has been updated.',
                ['for ' => session('org_id')]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception);
        }

        return false;
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

    /**
     * Store the organisations from ajax request.
     * Store in organization_data table.
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function store($id, $data)
    {
        try {
            foreach (array_get($data, 'organisation') as $organisation) {
                $organisation['is_publisher']     = (array_get($organisation, 'is_publisher', '0') === '1') ? true : null;
                $organisation['organization_id']  = $id;
                $organisation['is_reporting_org'] = false;
                $this->repo->storeOrgData($organisation);
            }

            return true;
        } catch (Exception $exception) {

            return false;
        }
    }

    public function update($orgDataId, $orgData)
    {
        try {
            return $this->repo->updateOrganizationData($orgDataId, $orgData);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Deletes record
     *
     * @param $id
     * @return mixed
     */
    public function delete($orgData)
    {
        return $this->repo->delete($orgData);
    }

    /**
     * Returns partner organizations of the given id.
     *
     * @param $orgId
     */
    public function getPartnerOrganizations($orgId)
    {
        return $this->repo->getPartnerOrganizations($orgId);
    }

    /**
     * Unpublish an OrganizationData.
     *
     * @param Organization $organization
     * @param              $organizationDataId
     * @return bool|null
     */
    public function unpublishOrganization(Organization $organization, $organizationDataId)
    {
        try {
            $organizationData          = $this->findOrganizationData($organizationDataId);
            $organizationPublished     = $organization->organizationPublished;
            $publishedOrganizationData = $organizationPublished->published_org_data;
            $xmlService                = $this->orgElement->getOrgXmlService();

            if (in_array($organizationDataId, $publishedOrganizationData)) {
                $remainingOrganizationData                 = array_flip(array_except(array_flip($publishedOrganizationData), $organizationDataId));
                $organizationPublished->published_org_data = array_values($remainingOrganizationData);
                $organizationPublished->save();
            }

            $organizationData->status = 0;
            $organizationData->save();

            $xmlService->generateOrgXml($organization, $organizationData, $organization->settings, $this->orgElement, true);

            $this->logger->info(
                sprintf('OrganizationData successfully unlinked for organization with id %s', $organization->id),
                [
                    'user' => auth()->user()->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error while unlinking OrganizationData with id %s', $organizationDataId),
                [
                    'trace' => $exception->getTraceAsString(),
                    'user'  => auth()->user()->id
                ]
            );

            return null;
        }
    }
}

