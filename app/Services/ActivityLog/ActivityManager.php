<?php namespace App\Services\ActivityLog;

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

    /**
     * @param UserActivity $activity
     * @param Guard        $auth
     */
    public function __construct(UserActivity $activity, Guard $auth)
    {

        $this->activity = $activity;
        $this->auth     = $auth;
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

}