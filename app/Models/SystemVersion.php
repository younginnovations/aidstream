<?php namespace App\Models;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemVersion
 * @package App\Models
 */
class SystemVersion extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'system_versions';

    /**
     * Fillable property for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['system_version'];

    /**
     * A SystemVersion belongs to an Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class, 'system_version_id');
    }
}
