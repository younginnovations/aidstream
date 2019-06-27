<?php namespace App\SuperAdmin\Services;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\Version;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Services\Export\CsvGenerator;
use App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin as SuperAdminInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;


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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Versions
     */
    protected $versions;

    /**
     * @param SuperAdminInterface   $adminInterface
     * @param CsvGenerator          $generator
     * @param User                  $user
     * @param ActivityPublished     $activityPublished
     * @param OrganizationPublished $organizationPublished
     * @param Activity              $activity
     * @param LoggerInterface       $logger
     * @param Version               $version
     */
    function __construct(
        SuperAdminInterface $adminInterface,
        CsvGenerator $generator,
        User $user,
        ActivityPublished $activityPublished,
        OrganizationPublished $organizationPublished,
        Activity $activity,
        LoggerInterface $logger,
        Version $version
    ) {
        $this->adminInterface        = $adminInterface;
        $this->user                  = $user;
        $this->activity              = $activity;
        $this->activityPublished     = $activityPublished;
        $this->organizationPublished = $organizationPublished;
        $this->generator             = $generator;
        $this->logger                = $logger;
        $this->version              = $version;
    }

    /**
     * return all organizations
     * @return mixed
     */
    public function getOrganizations($organizationName = null, $version =null)
    {
        return $this->adminInterface->getOrganizations($organizationName, $version);
    }

    /**
     * return all organizations
     * @return mixed
     */
    public function getVersions()
    {
        return $this->adminInterface->getVersions();
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
        try {
            $organizationDetails = [];
            $organizations       = Organization::leftJoin('activity_data', 'organizations.id', '=', 'activity_data.organization_id')
                                               ->leftJoin('settings', 'organizations.id', '=', 'settings.organization_id')
                                               ->leftJoin('organization_published', 'organizations.id', '=', 'organization_published.organization_id')
                                               ->leftJoin('users', 'organizations.id', '=', 'users.org_id')
                                               ->where('users.role_id', '=', '1')
                                               ->select(
                                                   'organizations.id',
                                                   'organizations.name',
                                                   'users.email',
                                                   'organizations.country',
                                                   'organizations.created_at as createdDate',
                                                   'settings.version',
                                                   DB::raw('count(activity_data.id) as noOfActivities'),
                                                   DB::raw('max(activity_data.updated_at) as lastUpdatedAt'),
                                                   DB::raw("concat(organizations.registration_agency, '-',organizations.registration_number) as registrationNumber"),
                                                   'organization_published.published_to_register as organizationDataPublishedStatus',
                                                   'organization_published.updated_at as organizationLastUpdatedAt'
                                               )
                                               ->groupBy('organizations.id', 'settings.id', 'users.id', 'organization_published.id')
                                               ->get();

            foreach ($organizations as $index => $organization) {
                $organizationId                                                       = $organization->id;
                $organizationDetails[$organizationId]['Id']                           = $organization->id;
                $organizationDetails[$organizationId]['Name']                         = $organization->name;
                $organizationDetails[$organizationId]['Admin Email']                  = $organization->email;
                $organizationDetails[$organizationId]['No. of Activities']            = $organization->noofactivities;
                $organizationDetails[$organizationId]['No. of Activities Published']  = $organization->activities()->where('published_to_registry', 1)->count();
                $organizationDetails[$organizationId]['Activity Last Updated At']     = $organization->lastupdatedat;
                $organizationDetails[$organizationId]['Organisation Data']            = (count($organization->organizationPublished) > 0) ? 1 : 0;
                $organizationDetails[$organizationId]['Organisation Data Published']  = $organization->organizationDataPublishedStatus;
                $organizationDetails[$organizationId]['Organisation Last Updated At'] = $organization->organizationLastUpdatedAt;
                $organizationDetails[$organizationId]['Country']                      = $organization->country;
                $organizationDetails[$organizationId]['Organisation Identifier']      = $organization->registrationnumber;
                $organizationDetails[$organizationId]['Publisher Id']                 = $organization->settings->registry_info ? getVal(
                    $organization->settings->registry_info,
                    [0, 'publisher_id'],
                    ''
                ) : '';
                $organizationDetails[$organizationId]['Registration Date']            = $organization->createdDate;
                $organizationDetails[$organizationId]['IATI Version']                 = $organization->version;
                $organizationDetails[$organizationId]['Visible in Who\'s Using It?']  = (count($organization->organizationSnapshot) > 0) ? 1 : 0;
            }

            return $organizationDetails;
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Failed to download csv due to : %s', $exception->getMessage()), ['trace' => $exception->getTraceAsString()]);

            return [];
        }
    }

    /**
     *  Exports the organization details to csv format
     */
    public function exportDetails()
    {
        $organizationDetails = $this->getAllOrganizationInfo();

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
}

