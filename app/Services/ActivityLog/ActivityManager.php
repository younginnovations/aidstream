<?php namespace App\Services\ActivityLog;

use App\Core\V201\Repositories\UserActivity;
use App\Core\Version;
use App\Services\Organization\OrganizationManager;
use Illuminate\Contracts\Auth\Guard;
use App\Services\Activity\ActivityManager as ActivitiesManager;
use Illuminate\Pagination\Paginator;


/**
 * Class ActivityManager
 */
class ActivityManager
{
    /**
     * @var Guard
     */
    private $auth;
    protected $repo;
    /**
     * @var Version
     */
    private $version;
    /**
     * @var UserActivity
     */
    protected $userActivityRepo;

    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @var ActivitiesManager
     */
    protected $activityManager;

    /**
     * @param OrganizationManager $organizationManager
     * @param ActivitiesManager   $activityManager
     * @param UserActivity        $userActivityRepo
     * @param Guard               $auth
     * @param Version             $version
     */
    public function __construct(OrganizationManager $organizationManager, ActivitiesManager $activityManager, UserActivity $userActivityRepo, Guard $auth, Version $version)
    {
        $this->auth                = $auth;
        $this->repo                = $version->getActivityElement()->getRepository();
        $this->version             = $version;
        $this->userActivityRepo    = $userActivityRepo;
        $this->organizationManager = $organizationManager;
        $this->activityManager     = $activityManager;
    }


    /**
     * Save user Activity
     *
     * @param       $action
     * @param array $param
     * @param array $data
     * @return UserActivity
     */
    public function save($action, array $param = [], array $data = [])
    {
        $activityData = [
            'action' => $action,
            'param'  => $param,
            'data'   => $data
        ];
        if ($userId = getVal($data, ['user_id'])) {
        } elseif (session('role_id') == '3' || session('role_id') == '4') {
            $userId = session('admin_id');
        } else {
            $userId = $this->auth->id();
        }
        $activityData['user_id']         = $userId;
        $activityData['organization_id'] = session('org_id');

        return $this->userActivityRepo->save($activityData);
    }

    /**
     * @param $id
     * @return model
     */
    public function getActivityData($id)
    {
        return $this->repo->getActivityData($id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getUserActivity($id)
    {
        return $this->userActivityRepo->getUserActivity($id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getUserActivityData($id)
    {
        $userActivityRow = $this->getUserActivity($id);

        return $userActivityRow ? $userActivityRow->data : '';
    }

    /**
     * @param $orgId
     * @return \App\Models\UserActivity
     */
    public function getUserActivities($orgId)
    {
        if ($orgId != "all") {
            return $this->userActivityRepo->getOrganizationActivities($orgId)->paginate(20);
        } else {
            return $this->userActivityRepo->getAllActivities()->paginate(20);
        }

    }

    /**
     * check if user activity is of super admin
     * @param $orgId
     * @return bool
     */
    protected function isSuperAdminActivity($orgId)
    {
        return ($orgId && strpos($orgId, 'sa-') !== false);
    }

    /** Returns all the users of the organization
     * @param $orgId
     * @return mixed
     */
    public function getUsersOfOrganization($orgId)
    {
        $users = $this->userActivityRepo->getUsersOfOrganization($orgId);

        return $users;
    }

    /** Returns all the activities of the organization
     * @param $orgId
     * @return \App\Models\Activity\Activity
     */
    public function getActivitiesOfOrganization($orgId)
    {
        $activities = $this->activityManager->getActivities($orgId);

        return $activities;
    }

    /** Returns result according to the selection of the filter.
     * @param $userSelection
     * @param $dataSelection
     * @return mixed
     */
    public function getResult($userSelection, $dataSelection)
    {
        return $this->userActivityRepo->getResult($userSelection, $dataSelection);
    }
}