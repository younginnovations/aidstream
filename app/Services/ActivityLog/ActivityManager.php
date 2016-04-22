<?php namespace App\Services\ActivityLog;

use App\Core\Version;
use App\Models\UserActivity;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class ActivityManager
 */
class ActivityManager
{

    /**
     * @var UserActivity
     */
    protected $activity;
    /**
     * @var Guard
     */
    private $auth;
    protected $repo;

    /**
     * @param UserActivity $activity
     * @param Guard        $auth
     * @param Version      $version
     */
    public function __construct(UserActivity $activity, Guard $auth, Version $version)
    {
        $this->activity = $activity;
        $this->auth     = $auth;
        $this->repo     = $version->getActivityElement()->getRepository();
    }


    /**
     * Save user Activity
     *
     * @param       $action
     * @param array $param
     * @return UserActivity
     */
    public function save($action, array $param = [])
    {
        $activityData = [
            'action' => $action,
            'param'  => $param
        ];
        if (session('role_id') == '3' || session('role_id') == '4') {
            $userId = session('admin_id');
        } else {
            $userId = $this->auth->id();
        }
        $activityData['user_id'] = $userId;

        return $this->activity->create($activityData);
    }

    /**
     * @param $id
     * @return model
     */
    public function getActivityData($id)
    {
        return $this->repo->getActivityData($id);
    }
}