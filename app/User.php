<?php namespace App;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Session;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthorizableContract, AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array (auth()->user() && auth()->user()->role_id == 3) ? url(config('app.super_admin_dashboard')) : config('app.admin_dashboard')
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
     * user belongs to organization
     */
    protected function organization()
    {
        return $this->belongsTo('App\Models\Organization\Organization', 'org_id');
    }

    /**
     * get user full name
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * get user detail by organization and role id
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
     * check if the user is superadmin or not
     * @return bool
     */
    public function isSuperAdmin()
    {
        return 3 === $this->role_id;
    }

    /**
     * check if the user is admin or not
     * @return bool
     */
    public function isAdmin()
    {
        return 1 == $this->role_id;
    }

    /**
     * check the user permission
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->user_permission);
    }

    /**
     * get the user_group associated with user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne('App\Models\SuperAdmin\UserGroup');
    }

    /**
     * check if user is groupadmin or not
     * @return bool
     */
    public function isGroupAdmin()
    {
        return 4 == $this->role_id;
    }

    /**
     * get the role id
     * @param $role
     * @return mixed
     */
    public function getRoleId($role)
    {
        return DB::select('select id from role where role = :role', ['role' => $role]);
    }

    /**
     * user has many activity log
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
        return ($this->isGroupAdmin() || $this->isSuperAdmin() || $this->organization->status);
    }

    /**
     * @return bool
     */
    public function getVerifiedStatusAttribute()
    {
        return ($this->isGroupAdmin() || $this->isSuperAdmin() || $this->verified);
    }

    public function getSuperAdmins()
    {
        return $this->where('org_id', null)->get();
    }

    public function getRolesByOrgAndUser($orgId, $userId)
    {
        $roles = DB::table($this->table)
                   ->join('role', 'role.id', '=', 'users.role_id')
                   ->where('users.org_id', '=', $orgId)
                   ->where('users.id', '=', $userId)
                   ->first();

        return $roles;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function userOnBoarding()
    {
        return $this->hasOne('App\Models\UserOnBoarding', 'user_id');
    }

}
