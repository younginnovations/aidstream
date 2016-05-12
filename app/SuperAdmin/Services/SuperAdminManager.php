<?php namespace App\SuperAdmin\Services;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
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
    function __construct(SuperAdminInterface $adminInterface, CsvGenerator $generator, User $user, ActivityPublished $activityPublished, OrganizationPublished $organizationPublished, Activity $activity)
    {
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
        $organizations       = Organization::all();
        $organizationDetails = [];

        $organizations->each(
            function ($organization) use (&$organizationDetails) {
                $organizationId                                                    = $organization->id;
                $organizationDetails[$organizationId]['name']                      = $organization->name;
                $organizationDetails[$organizationId]['admin_email']               = $this->getEmailOfOrganizationAdmin($organizationId);
                $organizationDetails[$organizationId]['noOfActivities']            = $this->getNoOfActivities($organizationId)[0]->noofactivities;
                $organizationDetails[$organizationId]['noOfActivitiesPublished']   = $this->getNoOfActivitiesPublished($organizationId)[0]->noofpublishedactivities;
                $organizationDetails[$organizationId]['organizationDataPublished'] = $this->statusOfOrganizationDataPublished($organizationId)[0]->organizationdatapublished;
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
        $headers = ['Organization Name', 'Admin Email','No. of Activities', 'No. of Published activities', 'Organization Data Published'];

        $this->generator->generateWithHeaders("Organization details", $organizationDetails, $headers);
    }

    /** Returns no. of activities present in the organization
     * @param $orgId
     * @return mixed
     */
    public function getNoOfActivities($orgId)
    {
        return $this->activity->getNoOfActivities($orgId);
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
}
