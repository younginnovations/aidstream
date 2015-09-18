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
        'org_id',
        'role_id',
        'user_permission'
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
        return  null === $this->role_id;
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
}

