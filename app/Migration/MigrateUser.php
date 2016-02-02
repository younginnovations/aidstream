<?php namespace App\Migration;

use App\Migration\Elements\UserPermission;
use Illuminate\Database\DatabaseManager;
use App\User;


class MigrateUser
{
    protected $orgId;
    protected $migrateHelper;
    protected $activityData;
    protected $userPermission;
    protected $user;

    function __construct(MigrateHelper $migrateHelper, DatabaseManager $databaseManager, ActivityData $activityData, UserPermission $userPermission, User $User)
    {
        $this->mysqlConn      = $databaseManager->connection('mysql');
        $this->migrateHelper  = $migrateHelper;
        $this->activityData   = $activityData;
        $this->userPermission = $userPermission;
        $this->user           = $User;
    }

    public function userDataFetch($user_id)
    {
        $formattedData       = [];
        $user                = [];
        $user_permission     = [];
        $orgId               = null;
        $newPermissionFormat = null;
        $userData            = $this->mysqlConn->table('user')
                                               ->select('*')
                                               ->where('user_id', '=', $user_id)
                                               ->first();

        $accountData = $this->mysqlConn->table('account')
                                       ->select('*')
                                       ->where('id', '=', $userData->account_id)
                                       ->first();

        $profile = $this->mysqlConn->table('profile')
                                   ->select('*')
                                   ->where('user_id', '=', $user_id)
                                   ->first();

        if ($userData->account_id != '0') {
            //  $orgId = $this->migrateHelper->fetchOrgId($userData->account_id);
            $orgData = $this->mysqlConn->table('iati_organisation')
                                       ->select('*')
                                       ->where('account_id', '=', $userData->account_id)
                                       ->first();
            if (!empty($orgData)) {
                $orgId = $orgData->id;
            }
        }
        $userPermissionData = $this->mysqlConn->table('user_permission')
                                              ->select('object')
                                              ->where('user_id', '=', $user_id)
                                              ->first();

        if (!empty($userPermissionData)) {
            $user_permission     = unserialize($userPermissionData->object);
            $arrayUserPermission = (array) $user_permission;
            $newPermissionFormat = $this->userPermission->format($arrayUserPermission);
        }

        $user = array(
            'id'              => $user_id,
            'first_name'      => $profile->first_name,
            'last_name'       => $profile->last_name,
            'email'           => $userData->email,
            'username'        => $userData->user_name,
            'password'        => $userData->password,
            'role_id'         => $userData->role_id,         //here continue
            'org_id'          => $orgId,
            'user_permission' => $newPermissionFormat
        );

        return $user;
    }

    public function hasPermission($permission)
    {
        return $this->$permission;
    }
}