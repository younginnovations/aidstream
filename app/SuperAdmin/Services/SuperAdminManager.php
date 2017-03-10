<?php namespace App\SuperAdmin\Services;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Services\Export\CsvGenerator;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin as SuperAdminInterface;
use App\User;


/**
 * Class SuperAdminManager
 * @package App\SuperAdmin\Services
 */
class SuperAdminManager
{
    /**
     * @var SuperAdminInterface
     */
    protected $adminInterface;

    /**
     * @var User
     */
    protected $user;
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;
    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     * @var CsvGenerator
     */
    protected $generator;

    /**
     * @param SuperAdminInterface   $adminInterface
     * @param CsvGenerator          $generator
     * @param User                  $user
     * @param ActivityPublished     $activityPublished
     * @param OrganizationPublished $organizationPublished
     * @param Activity              $activity
     */
    function __construct(
        SuperAdminInterface $adminInterface,
        CsvGenerator $generator,
        User $user,
        ActivityPublished $activityPublished,
        OrganizationPublished $organizationPublished,
        Activity $activity
    ) {
        $this->adminInterface        = $adminInterface;
        $this->user                  = $user;
        $this->activity              = $activity;
        $this->activityPublished     = $activityPublished;
        $this->organizationPublished = $organizationPublished;
        $this->generator             = $generator;
    }

    /**
     * return all organizations
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->adminInterface->getOrganizations();
    }

    /**
     * get organization by its id
     * @param $id
     * @return mixed
     */
    public function getOrganizationById($id)
    {
        return $this->adminInterface->getOrganizationById($id);
    }

    /**
     * get organization with user information
     * @param $id
     * @return mixed
     */
    public function getOrganizationUserById($id)
    {
        return $this->adminInterface->getOrganizationUserById($id);
    }

    /**
     * Returns list of organisations registered for the given version id.
     *
     * @param $id
     * @return mixed
     */
    public function getOrganizationBySystemVersion($id)
    {
        return $this->adminInterface->getOrganizationBySystemVersion($id);
    }

    /**
     * saves organization
     * @param null  $id
     * @param array $orgDetails
     * @return mixed
     */
    public function saveOrganization(array $orgDetails, $id = null)
    {
        return $this->adminInterface->saveOrganization($orgDetails, $id);
    }

    /**
     *  Details of the organization to be exported to the csv
     * @return array
     */
    public function getAllOrganizationInfo()
    {
        $organizations = Organization::with(
            [
                'activities' => function ($query) {
                    return $query->orderBy('updated_at', 'desc');
                },
                'settings',
                'organizationPublished',
                'organizationSnapshot',
                'users'      => function ($query) {
                    return $query->where('role_id', 1);
                }
            ]
        )->get();

        $noOfPublishedActivities = $this->getNumberOfPublishedActivitiesForOrg($organizations);
        $organizationDetails     = [];

        $organizations->each(
            function ($organization) use (&$organizationDetails, $noOfPublishedActivities) {
                $organizationId                                                       = $organization->id;
                $organizationDetails[$organizationId]['Name']                         = $organization->name;
                $organizationDetails[$organizationId]['Admin Email']                  = getVal($organization->users->toArray(), [0, 'email'], '');
                $organizationDetails[$organizationId]['No. of Activities']            = count($organization->activities);
                $organizationDetails[$organizationId]['Activity Last Updated At']     = getVal($organization->activities->toArray(), [0, 'updated_at'], '');
                $organizationDetails[$organizationId]['No. of Activities Published']  = getVal($noOfPublishedActivities, [$organizationId], 0);
                $organizationDetails[$organizationId]['Organisation Data Published']  = $organization->published_to_registry;
                $organizationDetails[$organizationId]['Organisation Last Updated At'] = $organization->updated_at ? $organization->updated_at->format('Y-m-d h:m:s') : '';
                $organizationDetails[$organizationId]['Country']                      = $organization->country;
                $organizationDetails[$organizationId]['Organisation Identifier']      = ($organization->registration_agency && $organization->registration_number) ? $organization->registration_agency . '-' . $organization->registration_number : '';
                $organizationDetails[$organizationId]['Publisher Id']                 = $organization->settings->registry_info ? getVal(
                    $organization->settings->registry_info,
                    [0, 'publisher_id'],
                    ''
                ) : '';
                $organizationDetails[$organizationId]['Registration Date']            = $organization->created_at->format('Y-m-d h:m:s');
                $organizationDetails[$organizationId]['IATI Version']                 = $organization->settings->version;
                $organizationDetails[$organizationId]['Organisation Data']            = (count($organization->organizationPublished) > 0) ? 1 : 0;
                $organizationDetails[$organizationId]['Visible in Who\'s Using It?']  = (count($organization->organizationSnapshot) > 0) ? 1 : 0;

            }
        );

        return $organizationDetails;
    }

    /**
     *  Exports the organization details to csv format
     * @param $organizationDetails
     */
    public function exportDetails($organizationDetails)
    {
        $headers = [
            'Organization Name',
            'Admin Email',
            'No. of Activities',
            'Activity Last Updated At',
            'No. of Published activities',
            'Organization Data Published',
            'Organization Data Last Updated At',
            'Country',
            'Organization Identifier',
            'Publisher Id',
            'Registration Date',
            'IATI Version',
            'Organization Data',
            "Visible in who's using?"
        ];

        $this->generator->generateWithHeaders("Organization details", $organizationDetails, $headers);
    }

    /** Returns no. of activities and last updated date of activity of given organization
     * @param $orgId
     * @return mixed
     */
    public function getActivitiesData($orgId)
    {
        $activitiesData = $this->activity->getActivitiesData($orgId);

        return $activitiesData;
    }

    /** Returns no. of activities published in the registry.
     * @param $orgId
     * @return mixed
     */
    public function getNoOfActivitiesPublished($orgId)
    {
        return $this->activityPublished->getNoOfActivitiesPublished($orgId);
    }

    /** Returns status of the organization data
     * @param $orgId
     * @return mixed
     */
    public function statusOfOrganizationDataPublished($orgId)
    {
        return $this->organizationPublished->statusOfOrganizationDataPublished($orgId);
    }

    /** Returns email of the admin of the organization.
     * @param $orgId
     * @return string
     */
    public function getEmailOfOrganizationAdmin($orgId)
    {
        $userDetails = $this->user->getDataByOrgIdAndRoleId($orgId, 1);

        return $userDetails ? $userDetails->email : '';
    }

    /**
     * @param $organizations
     * @return array
     */
    protected function getNumberOfPublishedActivitiesForOrg($organizations)
    {
        $noOfPublishedActivities = [];
        foreach ($organizations as $index => $organization) {
            $noOfPublishedActivities[$organization->id] = 0;
            foreach ($organization->activities as $i => $activity) {
                if ($organization->activities[$i]->published_to_registry) {
                    $noOfPublishedActivities[$organization->id] ++;
                }
            }
        }

        return $noOfPublishedActivities;
    }
}
