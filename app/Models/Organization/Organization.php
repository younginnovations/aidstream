<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Class Organization
 * @package App\Models\Organization
 */
class Organization extends Model
{
    protected $table = "organizations";
    protected $fillable = ['name', 'address', 'user_identifier', 'reporting_org', 'status', 'country', 'twitter', 'organization_url', 'logo', 'logo_url', 'disqus_comments', 'published_to_registry'];
    protected $casts = ['reporting_org' => 'json'];

    /**
     * organization has many users
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', 'org_id');
    }

    /**
     * organization has many activities
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity\Activity', 'organization_id');
    }

    /**
     * organization has many documents
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'org_id');
    }

    /**
     * get organization status
     * @return string
     */
    public function getOrgStatusAttribute()
    {
        return ($this->status == 1) ? 'Enabled' : 'Disabled';
    }

    /**
     * get organization details
     * @return mixed
     */
    public function getOrganization()
    {
        $organization = DB::table($this->table)
                          ->where('id', '=', Session::get('org_id'))
                          ->get();

        return $organization;
    }

    /**
     * organization has many settings
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasOne('App\Models\Settings', 'organization_id');
    }

    /**
     * Get Admin User.
     * If no Admin User is present, returns an arbitrary user.
     * @return mixed
     */
    public function getAdminUser()
    {
        return ($user = $this->users->where('role_id', '=', 1)->first()) ? $user : $this->users()->first();
    }
}
