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
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrgData($id, $request)
    {
        $participatingOrganization = $request->all();
        $allOrganizationData       = $this->organizationRepository->getOrganizationDataFor(session('org_id'));

        try {
            foreach (getVal($participatingOrganization, ['participating_organization']) as $item => $value) {
                $orgData         = [];
                $oldOrganization = $this->checkForNewOrganization(getVal($value, ['identifier']), getVal($value, ['narrative', 0, 'narrative']), $allOrganizationData);
                if (!$oldOrganization) {
                    $value['organization_id']  = session('org_id');
                    $value['name']             = $value['narrative'];
                    $value['used_by']          = [+ $id];
                    $value['is_reporting_org'] = false;
                    $value['type']             = $value['organization_type'];
                    $orgData                   = $this->organizationRepository->storeOrgData($value);
                } else {
                    $usedBy = $oldOrganization->used_by;
                    $match  = array_first(
                        $usedBy,
                        function ($key, $value) use ($id) {
                            return $value == $id;
                        }
                    );
                    if (!$match) {
                        array_push($usedBy, + $id);
                        $oldOrganization->used_by = $usedBy;
                        $oldOrganization->save();
                    }
                }

                if (!getVal($value, ['org_data_id'], null) && !empty($orgData)) {
                    $participatingOrganization['participating_organization'][$item]['org_data_id'] = $orgData->id;
                }
            }
        } catch (\Exception $exception) {
            return response()->json($exception, 400);
        }

        return $participatingOrganization;
    }

    /**
     * Check if the organization exists in our database.
     *
     * @param $identifier
     * @param $name
     * @param $allOrganizationData
     * @return bool
     */
    private function checkForNewOrganization($identifier, $name, $allOrganizationData)
    {
        foreach ($allOrganizationData as $item => $value) {
            if ($value->identifier == $identifier || $value->name[0]['narrative'] == $name) {
                return $value;
            }
        }

        return false;
    }
}
