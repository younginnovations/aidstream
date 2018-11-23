<?php namespace App\Models\Organization;

use App\Models\ActivityPublished;
use App\Models\OrganizationPublished;
use App\Models\PerfectViewer\OrganizationSnapshot;
use App\Models\SystemVersion;
use App\User;
use Illuminate\Database\Eloquent\Collection;
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
    protected $fillable = [
        'name',
        'address',
        'user_identifier',
        'reporting_org',
        'status',
        'country',
        'twitter',
        'organization_url',
        'logo',
        'logo_url',
        'disqus_comments',
        'published_to_registry',
        'registration_agency',
        'registration_number',
        'secondary_contact',
        'system_version_id',

    ];
    protected $casts = ['reporting_org' => 'json', 'secondary_contact' => 'json'];

    /**
     * organization one organization data
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orgData()
    {
        return $this->hasMany('App\Models\Organization\OrganizationData', 'organization_id');
    }
    
    public function organizationLocation()
    {
        return $this->hasMany('App\Models\Organization\OrganizationLocation','organization_id');
    }

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
        return ($user = $this->users()->where('role_id', '=', 1)->first()) ? $user : $this->users()->first();
    }

    /**
     * Get AdminUserId.
     * @return mixed
     */
    public function adminUserId()
    {
        return ($user = app()->make(User::class)->where('org_id', '=', $this->id)->where('role_id', '=', 1)->first()) ? $user->id : $this->users()->first()->id;
    }

    /**
     * An Organization can have many Activities published.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publishedFiles()
    {
        return $this->hasMany(ActivityPublished::class);
    }

    /**
     * An Organization can have many Organization Published files.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organizationPublished()
    {
        return $this->hasOne(OrganizationPublished::class);
    }

    /**
     * get organization name
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ($name = getVal((array) $this->reporting_org, [0, 'narrative', 0, 'narrative'])) ? $name : $value;
    }

    /**
     * An Organization belongs to SystemVersion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function systemVersion()
    {
        return $this->belongsTo(SystemVersion::class, 'system_version_id');
    }

    /**
     * An Organization has one OrganizationSnapshot
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organizationSnapshot()
    {
        return $this->hasOne(OrganizationSnapshot::class, 'org_id');
    }

    /**
     * Get the Partner Organizations for a Reporting Organization.
     *
     * @return Collection
     */
    public function partners()
    {
        return $this->orgData()->where('is_reporting_org', 'false')->get();
    }

    /**
     * Get the Organisation Identifier Attribute.
     *
     * @return mixed
     */
    public function getIdentifierAttribute()
    {
        return array_get($this->reporting_org, '0.reporting_organization_identifier', '');
    }
}
