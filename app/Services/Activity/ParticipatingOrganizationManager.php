<?php namespace App\Services\Activity;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\ParticipatingOrganizations\PartnerOrganizationData;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Excel;

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
    protected $organizationRepository;

    protected $participatingOrgRepo;

    /**
     * @var PartnerOrganizationData
     */
    protected $partnerOrganization;

    /**
     * @var Excel
     */
    protected $excel;

    protected $filename = 'foundapi.csv';

    /**
     * @param Version                $version
     * @param OrganizationManager    $organizationManager
     * @param OrganizationRepository $organizationRepository
     * @param Excel                  $excel
     * @param Log                    $log
     * @param Guard                  $auth
     */
    public function __construct(
        Version $version,
        OrganizationManager $organizationManager,
        OrganizationRepository $organizationRepository,
//        PartnerOrganizationData $partnerOrganization,
        Excel $excel,
        Log $log,
        Guard $auth
    ) {
        $this->auth                   = $auth;
        $this->log                    = $log;
        $this->participatingOrgRepo   = $version->getActivityElement()
                                                ->getParticipatingOrganization()
                                                ->getRepository();
        $this->version                = $version;
        $this->organizationManager    = $organizationManager;
        $this->organizationRepository = $organizationRepository;
        $this->excel                  = $excel;
//        $this->partnerOrganization    = $partnerOrganization;

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
     * Manage Partners for an Activity.
     *
     * @param Activity $activity
     * @param array    $participatingOrganizationDetails
     * @return array|null
     */
    public function managePartnerOrganizations(Activity $activity, $participatingOrganizationDetails = null, $data = null)
    {
        try {
            $this->partnerOrganization = app()->make(PartnerOrganizationData::class);

            if (!$participatingOrganizationDetails) {
                $participatingOrganizations = $activity->participating_organization ? $activity->participating_organization : [];
            } else {
                $participatingOrganizations = array_get($participatingOrganizationDetails, 'participating_organization', []);
            }

            $this->partnerOrganization->init($activity, $participatingOrganizations, $this->organizationRepository, $data)
                                      ->sync();
        } catch (Exception $exception) {
            $this->log->error(
                $exception->getMessage(),
                [
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }
}
