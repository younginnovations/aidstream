<?php namespace App\Tz\Aidstream\Models;

use App\Models\Organization\Organization as AidstreamOrganization;

/**
 * Class Organization
 * @package App\Tz\Aidstream\Models
 */
class Organization extends AidstreamOrganization
{
    /**
     * Table name.
     * @var string
     */
    protected $table = "organizations";

    /**
     * Fillable property for mass assignment.
     * @var array
     */
    protected $fillable = ['name', 'address', 'user_identifier', 'reporting_org', 'status', 'country', 'twitter', 'organization_url', 'logo', 'logo_url', 'disqus_comments', 'published_to_registry'];

    /**
     * @var array
     */
    protected $casts = ['reporting_org' => 'json'];

    /**
     * An Organization has many Projects.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'organization_id', 'id');
    }

}
