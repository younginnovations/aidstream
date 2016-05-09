<?php namespace App\Core\V201\Repositories;

use App\Models\Organization\Organization;
use App\Models\UserActivity as UserActivityModel;
use Illuminate\Support\Facades\DB;

/**
 * Class UserActivity
 * @package App\Core\V201\Repositories
 */
class UserActivity
{
    /**
     * @var UserActivityModel
     */
    protected $userActivity;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Query builder
     */
    protected $query;

    /**
     * @param UserActivityModel $userActivity
     * @param Organization      $organization
     */
    public function __construct(UserActivityModel $userActivity, Organization $organization)
    {
        $this->userActivity = $userActivity;
        $this->organization = $organization;
    }

    /**
     * create activity log
     * @param array $activityData
     * @return bool
     */
    public function save(array $activityData)
    {
        return $this->userActivity->create($activityData);
    }

    /**
     * return user activity
     * @param $id
     * @return UserActivityModel/null
     */
    public function getUserActivity($id)
    {
        return $this->userActivity->find($id);
    }

    /**
     * return user activity
     * @param $orgId
     * @return UserActivityModel /null
     */
    public function getAllActivities()
    {
        return $this->userActivity->join('users', 'users.id', '=', 'user_activities.user_id')
                                  ->join('organizations', 'organizations.id', '=', 'user_activities.organization_id')
                                  ->orderBy('user_activities.id', 'desc')
                                  ->select('*', 'user_activities.id as user_activity_id', 'user_activities.created_at as created_date');
    }

    public function getOrganizationActivities($orgId)
    {
        $this->query = $this->getAllActivities()->where('user_activities.organization_id', $orgId);

        return $this->query;
    }

    /** Returns all the logs of the organization
     * @param $orgId
     * @return mixed
     */
    public function getOrganizationLogs($orgId)
    {
        return $this->userActivity->join('users', 'users.id', '=', 'user_activities.user_id')
                                  ->join('organizations', 'organizations.id', '=', 'users.org_id')
                                  ->where('organizations.id', $orgId)
                                  ->orderBy('user_activities.id', 'desc')
                                  ->select('*', 'user_activities.id as user_activity_id', 'user_activities.created_at as created_date');
    }

    /**Returns all the users of the organization
     * @param $orgId
     * @return mixed
     */
    public function getUsersOfOrganization($orgId)
    {
        return $this->organization->findOrfail($orgId)->users;
    }


    /** Returns query according to the user selection
     * @param $userSelection
     * @return $this
     */
    protected function getUser($userSelection)
    {
        if ($userSelection != "all") {
            $this->query->where('user_id', $userSelection);
        }

        return $this;
    }

    /** Returns query according to the data selection
     * @param $dataSelection
     * @return $this
     */
    protected function getData($dataSelection)
    {
        if ($dataSelection != "all") {
            if ($dataSelection == "organization") {
                $this->query->where('action', 'LIKE', $dataSelection . '%');
            } else {
                $this->query->whereRaw("param ->> 'activity_id' = ?", [$dataSelection]);
            }
        }

        return $this;
    }

    /** Returns final result according to user and data selection
     * @param $userSelection
     * @param $dataSelection
     * @return mixed
     */
    public function getResult($userSelection, $dataSelection)
    {
        $this->query = $this->initializeBuilder();
        $this->getUser($userSelection)->getData($dataSelection);

        return $this->query->paginate(20);
    }

    /**
     * @return mixed
     */
    protected function initializeBuilder()
    {
        return $this->getOrganizationLogs(session('org_id'));
    }

}