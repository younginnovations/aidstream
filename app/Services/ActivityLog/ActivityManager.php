<?php namespace App\Services\ActivityLog;

use App\Core\V201\Repositories\UserActivity;
use App\Core\Version;
use Illuminate\Contracts\Auth\Guard;

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
     * @param UserActivity $userActivityRepo
     * @param Guard        $auth
     * @param Version      $version
     */
    public function __construct(UserActivity $userActivityRepo, Guard $auth, Version $version)
    {
        $this->auth             = $auth;
        $this->repo             = $version->getActivityElement()->getRepository();
        $this->version          = $version;
        $this->userActivityRepo = $userActivityRepo;
    }


    /**
     * Save user Activity
     *
     * @param       $action
     * @param array $param
     * @param array $data
     * @return UserActivity
     */
    public function save($action, array $param = [], array $data = null)
    {
        $activityData = [
            'action' => $action,
            'param'  => $param,
            'data'   => $data
        ];
        if (session('role_id') == '3' || session('role_id') == '4') {
            $userId = session('admin_id');
        } else {
            $userId = $this->auth->id();
        }
        $activityData['user_id'] = $userId;
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
        $type     = sprintf('get%sActivities', $this->isSuperAdminActivity($orgId) ? 'SuperAdmin' : 'Organization');
        $activity = $this->userActivityRepo->$type($orgId);

        return $activity;
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
}