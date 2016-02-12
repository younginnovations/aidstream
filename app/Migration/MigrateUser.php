<?php namespace App\Migration;

use App\Migration\Elements\UserPermission;
use Illuminate\Database\DatabaseManager;
use App\User;


class MigrateUser
{
    protected $orgId;
    protected $activityData;
    protected $userPermission;
    protected $user;
    protected $mysqlConn;

    function __construct(ActivityData $activityData, UserPermission $userPermission, User $User)
    {
        $this->activityData   = $activityData;
        $this->userPermission = $userPermission;
        $this->user           = $User;
    }

    public function userDataFetch($user)
    {
        $this->initDBConnection('mysql');

        $user_id   = $user->user_id;
        $accountId = $user->account_id;

        $newPermissionFormat = null;

        $profile = $this->mysqlConn->table('profile')
                                   ->select('*')
                                   ->where('user_id', '=', $user_id)
                                   ->first();

        $newOrgId           = $accountId;
        $userPermissionData = $this->mysqlConn->table('user_permission')
                                              ->select('object')
                                              ->where('user_id', '=', $user_id)
                                              ->first();

        if (!empty($userPermissionData)) {
            $arrayUserPermission = (array) unserialize($userPermissionData->object);
            $newPermissionFormat = $this->userPermission->format($arrayUserPermission);
        }

        $newUser = array(
            'id'              => $user_id,
            'first_name'      => $profile->first_name,
            'last_name'       => $profile->last_name,
            'email'           => $user->email,
            'username'        => $user->user_name,
            'password'        => $user->password,
            'role_id'         => $user->role_id,         //here continue
            'user_permission' => $newPermissionFormat
        );

        if (getOrganizationFor($accountId)) {
            $newUser['org_id'] = $newOrgId;
        } else {
            $newUser['org_id'] = null;
        }


        return $newUser;
    }

    public function hasPermission($permission)
    {
        return $this->$permission;
    }

    public function getUsersFor($accountId)
    {
        $this->initDBConnection('mysql');

        return $this->mysqlConn->table('user')->select('*')->where('account_id', '=', $accountId)->get();
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}