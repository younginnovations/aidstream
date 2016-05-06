<?php namespace App\Core\V201\Repositories;

use App\Models\UserActivity as UserActivityModel;

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
     * @param UserActivityModel $userActivity
     */
    public function __construct(UserActivityModel $userActivity)
    {
        $this->userActivity = $userActivity;
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
    public function getSuperAdminActivities($orgId)
    {
        return $this->userActivity->join('users', 'users.id', '=', 'user_activities.user_id')
                                  ->where('user_activities.user_id', str_replace('sa-', '', $orgId))
                                  ->orderBy('user_activities.id', 'desc')
                                  ->select('*', 'user_activities.id as user_activity_id', 'user_activities.created_at as created_date')
                                  ->get();
    }

    /**
     * return user activity
     * @param $orgId
     * @return UserActivityModel /null
     */
    public function getOrganizationActivities($orgId)
    {
        return $this->userActivity->join('users', 'users.id', '=', 'user_activities.user_id')
                                  ->join('organizations', 'organizations.id', '=', 'users.org_id')
                                  ->where('organizations.id', $orgId)
                                  ->orderBy('user_activities.id', 'desc')
                                  ->select('*', 'user_activities.id as user_activity_id', 'user_activities.created_at as created_date')
                                  ->get();
    }
}