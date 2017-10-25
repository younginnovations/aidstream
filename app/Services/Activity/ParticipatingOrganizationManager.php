<?php namespace App\Services\Activity;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ParticipatingOrganizationManager
 * @package app\Services\Activity
 */
class ParticipatingOrganizationManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var OrganizationRepository
     */
    private $organizationRepository;

    /**
     * @param Version                $version
     * @param OrganizationManager    $organizationManager
     * @param OrganizationRepository $organizationRepository
     * @param Log                    $log
     * @param Guard                  $auth
     */
    public function __construct(Version $version, OrganizationManager $organizationManager, OrganizationRepository $organizationRepository, Log $log, Guard $auth)
    {
        $this->auth                   = $auth;
        $this->log                    = $log;
        $this->participatingOrgRepo   = $version->getActivityElement()
                                                ->getParticipatingOrganization()
                                                ->getRepository();
        $this->version                = $version;
        $this->organizationManager    = $organizationManager;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * updates Activity Participating Organization
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->participatingOrgRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Participating Organization updated!',
                ['for' => $activity->participating_organization]
            );
            $this->log->activity(
                "activity.participating_organization",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error($exception, ['ParticipatingOrganization' => $activityDetails]);
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getParticipatingOrganizationData($id)
    {
        return $this->participatingOrgRepo->getParticipatingOrganizationData($id);
    }

    /**
     * @param $orgId
     * @return mixed
     */
    public function getPartnerOrganizations($orgId)
    {
        return $this->organizationManager->getPartnerOrganizations($orgId);
    }

    /**
     * Add new Organization data if not exist when participating organization is added.
     *
     * @param $id
     * @param $request
     * @return null|array
     */
    public function addOrgData($id, $request)
    {
        try {
            $participatingOrganization = $request->all();
            $allOrganizationData       = $this->organizationRepository->getOrganizationDataFor(session('org_id'));
            $oldUsedOrganizations      = $allOrganizationData->filter(
                function ($item) use ($id) {
                    $temp = [];

                    if (array_has(array_flip($item->used_by), $id)) {
                        $temp[] = $item;
                    }

                    return $temp;
                }
            );

            $this->manageOldOrganizations($oldUsedOrganizations, $participatingOrganization, $id);

            foreach (getVal($participatingOrganization, ['participating_organization']) as $item => $value) {
                $orgData         = [];
                $oldOrganization = $this->checkForNewOrganization($value, $allOrganizationData);

                if (!$oldOrganization || !array_get($value, 'identifier')) {
                    $orgData = $this->createNewPartnerOrganization($value, $id);
                } else {
                    $this->updateExistingPartnerOrganization($oldOrganization, $id);
                }

                if (!getVal($value, ['org_data_id'], null) && !empty($orgData)) {
                    $participatingOrganization['participating_organization'][$item]['org_data_id'] = $orgData->id;
                }
            }
        } catch (\Exception $exception) {
            $this->log->error($exception->getMessage(), ['trace' => $exception->getTraceAsString()]);

            return null;
        }

        return $participatingOrganization;
    }

    /**
     * Check if the organization exists in our database.
     *
     * @param $value
     * @param $allOrganizationData
     * @return bool
     */
    protected function checkForNewOrganization($value, $allOrganizationData)
    {
        foreach ($allOrganizationData as $item => $organisation) {
            if (!array_get($value, 'identifier')) {
                return false;
            }

            if ($this->organizationIsCompletelySame($organisation, $value)) {
                return $organisation;
            }
        }

        return false;
    }

    /**
     * Update old Organisations that has not been used anymore.
     *
     * @param $oldUsedOrganizations
     * @param $participatingOrganization
     * @param $id
     */
    protected function manageOldOrganizations($oldUsedOrganizations, $participatingOrganization, $id)
    {
        foreach ($oldUsedOrganizations as $oldUsedOrganization) {
            foreach ($participatingOrganization['participating_organization'] as $value) {
                if (($oldUsedOrganization->identifier != array_get($value, 'identifier')) && array_has(array_flip($oldUsedOrganization->used_by), $id)) {
                    $oldUsedBy = array_flip($oldUsedOrganization->used_by);
                    unset($oldUsedBy[$id]);
                    $oldUsedOrganization->used_by = array_flip($oldUsedBy);

                    $oldUsedOrganization->save();
                }
            }
        }
    }

    /**
     * Create a new Partner Organization.
     *
     * @param $value
     * @param $id
     * @return \App\Models\Organization\OrganizationData
     */
    protected function createNewPartnerOrganization($value, $id)
    {
        $value['organization_id']  = session('org_id');
        $value['name']             = $value['narrative'];
        $value['used_by']          = [+ $id];
        $value['is_reporting_org'] = false;
        $value['type']             = $value['organization_type'];
        $value['is_publisher']     = boolval($value['is_publisher']);

        return $this->organizationRepository->storeOrgData($value);
    }

    /**
     * Update existing Partner Organization.
     *
     * @param $oldOrganization
     * @param $id
     */
    protected function updateExistingPartnerOrganization($oldOrganization, $id)
    {
        $usedBy = $oldOrganization->used_by;
        $match  = false;

        foreach ($usedBy as $activityId) {
            if ($activityId == $id) {
                $match = true;
            }
        }

        if (!$match) {
            array_push($usedBy, + $id);
            $oldOrganization->used_by = $usedBy;

            $oldOrganization->save();
        }
    }

    /**
     * Check if the data of an Organisation being added is completely same as other Partner Organisations.
     *
     * @param $organization
     * @param $value
     * @return bool
     */
    protected function organizationIsCompletelySame($organization, $value)
    {
        if ($organization->identifier == array_get($value, 'identifier')
            && $organization->name[0]['narrative'] == array_get($value, 'narrative.0.narrative')
            && $organization->country == array_get($value, 'country')
            && $organization->type == array_get($value, 'organization_type')) {

            return true;
        }

        return false;
    }
}
