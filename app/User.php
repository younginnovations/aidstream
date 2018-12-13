<?php namespace App;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Session;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthorizableContract, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Authorizable;

    /**
     * Admin Role Id.
     */
    const ADMIN_ROLE_ID = 1;

    /**
     * Superadmin Role Id.
     */
    const SUPERADMIN_ROLE_ID = 3;

    /**
     * Groupadmin Role Id.
     */
    const GROUPADMIN_ROLE_ID = 4;

    /**
     * Administrator Role Id.
     */
    const ADMINISTRATOR_ROLE_ID = 5;

    /**
     * Municipality Admin Role Id.
     */
    const MUNICIPALITY_ADMIN_ROLE_ID = 8;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'country',
        'org_id',
        'role_id',
        'user_permission',
        'time_zone_id',
        'time_zone',
        'profile_url',
        'profile_picture',
        'time_zone',
        'verification_code',
        'verification_created_at',
        'verified'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_permission' => 'json'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    /**
     * User belongs to organization
     */
    protected function organization()
    {
        return $this->belongsTo('App\Models\Organization\Organization', 'org_id');
    }

    /**
     * Get user full name
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Get user detail by organization and role id
     * @return array|static[]
     */
    public function getUserByOrgIdAndRoleId()
    {
        $users = DB::table($this->table)
                   ->where('role_id', '=', 2)
                   ->where('org_id', '=', Session::get('org_id'))
                   ->get();

        return $users;
    }

    /**
     * Check if the user is Superadmin or not
     * @return bool
     */
    public function isSuperAdmin()
    {
        return self::SUPERADMIN_ROLE_ID === $this->role_id;
    }

    /**
     * Get Municipality Id of Admin
     * @return $id
     */
    public function getMunicipalityIdOfAdmin()
    {
        $municipality = DB::table('user_municipality')->where('user_id', $this->id)->first();

        return $municipality->municipality_id;
    }

    /**
     * Check if the user is Municipality Admin or not
     *
     * @return boolean
     */
    public function isMunicipalityAdmin()
    {
        if($this->role_id == self::MUNICIPALITY_ADMIN_ROLE_ID){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the user is admin or not
     * @return bool
     */
    public function isAdmin()
    {
        return (self::ADMIN_ROLE_ID == $this->role_id || self::ADMINISTRATOR_ROLE_ID == $this->role_id);
    }

    /**
     * Check the user permission
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!$this->user_permission) {
            return $this->doesUserHave($permission);
        }

        if (!$this->role) {
            return true;
        }

        return in_array($permission, json_decode($this->role->permissions, true));
    }

    /**
     * Get the user_group associated with user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne('App\Models\SuperAdmin\UserGroup');
    }

    /**
     * Check if user is groupadmin or not
     * @return bool
     */
    public function isGroupAdmin()
    {
        return (self::GROUPADMIN_ROLE_ID == $this->role_id);
    }

    /**
     * Get the role id
     * @param $role
     * @return mixed
     */
    public function getRoleId($role)
    {
        return DB::select('select id from role where role = :role', ['role' => $role]);
    }

    /**
     * User has many activity log
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLog()
    {
        return $this->hasMany('App\Models\UserActivity', 'user_id');
    }

    /**
     * @return mixed
     */
    public function getUserByOrgId()
    {
        $users = DB::table($this->table)
                   ->where('org_id', '=', Session::get('org_id'))
                   ->where('role_id', '=', 1)
                   ->get();

        return $users;
    }

    /**
     * Check if the current User is not an Admin user.
     * @return bool
     */
    public function isNotAdmin()
    {
        return (!$this->isAdmin() && !$this->isGroupAdmin() && !$this->isSuperAdmin());
    }

    /**
     * Check if the current User is not an Admin user.
     * @return bool
     */
    public function getDataByOrgIdAndRoleId($orgId, $roleId)
    {
        $users = DB::table($this->table)
                   ->where('org_id', '=', $orgId)
                   ->where('role_id', '=', $roleId)
                   ->first();

        return $users;
    }

    /**
     * @return bool
     */
    public function getEnabledAttribute()
    {
        return ($this->isGroupAdmin() || $this->isSuperAdmin() || $this->isMunicipalityAdmin() || $this->organization->status);
    }

    /**
     * @return bool
     */
    public function getVerifiedStatusAttribute()
    {
        return ($this->isGroupAdmin() || $this->isSuperAdmin() || $this->verified);
    }

    /**
     * @return mixed
     */
    public function getSuperAdmins()
    {
        return $this->where('org_id', null)->get();
    }

    /**
     * @param $orgId
     * @param $userId
     * @return mixed
     */
    public function getRolesByOrgAndUser($orgId, $userId)
    {
        $roles = DB::table($this->table)
                   ->join('role', 'role.id', '=', 'users.role_id')
                   ->where('users.org_id', '=', $orgId)
                   ->where('users.id', '=', $userId)
                   ->first();

        return $roles;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    /**
     * A User hasOne OnBoarding.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userOnBoarding()
    {
        return $this->hasOne('App\Models\UserOnBoarding', 'user_id');
    }

    /**
     * Check if the User has any specific permission.
     * @param $permission
     * @return bool
     */
    protected function doesUserHave($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        $userPermissions = json_decode($this->role->permissions, true);

        if (!empty($userPermissions)) {
            return in_array($this->extractPermission($permission), $this->breakPermissionsIntoActions($userPermissions));
        }

        return false;
    }

    /**
     * Break down user permissions into actions.
     * @param $userPermissions
     * @return array
     */
    protected function breakPermissionsIntoActions($userPermissions)
    {
        $actions = [];

        if (is_array($userPermissions)) {
            foreach ($userPermissions as $permission) {
                $actions[] = $this->extractPermission($permission);
            }
        }

        return $actions;
    }

    /**
     * Extract action from permission.
     * @param $permission
     * @return mixed
     */
    protected function extractPermission($permission)
    {
        $action = explode('_', $permission);

        return array_first(
            $action,
            function () {
                return true;
            }
        );
    }

    /**
     * Get the current Users system version.
     *
     * @return string
     */
    public function getSystemVersion()
    {
        return $this->organization ? $this->organization->systemVersion->system_version : 'Core';
    }
}
