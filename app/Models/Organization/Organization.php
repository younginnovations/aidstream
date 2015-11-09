<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 * @package App\Models\Organization
 */
class Organization extends Model
{
    protected $table = "organizations";
    protected $fillable = ['name', 'address', 'user_identifier', 'reporting_org', 'status'];
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

    public function getOrgStatusAttribute()
    {
        return ($this->status == 1) ? 'Enabled' : 'Disabled';
    }
}
