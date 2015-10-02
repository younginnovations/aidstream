<?php namespace App\Services\ActivityLog;

use App\Core\Version;
use App\Models\UserActivity;
use Illuminate\Auth\Guard;

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
            'user_id' => $this->auth->id(),
            'action'  => $action,
            'param'   => $param
        ];

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