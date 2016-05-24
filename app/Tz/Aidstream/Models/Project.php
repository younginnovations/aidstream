<?php namespace App\Tz\Aidstream\Models;

use App\Models\Activity\Activity;

/**
 * Class Project
 */
class Project extends Activity
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'activity_data';

    /**
     * Fillable property for mass assignment.
     * @var array
     */
    protected $fillable = [
        'identifier',
        'organization_id',
        'other_identifier',
        'title',
        'description',
        'activity_status',
        'recipient_country',
        'recipient_region',
        'participating_organization',
        'activity_date',
        'location',
        'sector'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'activity_id', 'id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'activity_id', 'id');
    }
}
