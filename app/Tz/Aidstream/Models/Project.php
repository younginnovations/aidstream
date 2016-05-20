<?php namespace App\Tz\Models;

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
    protected $fillable = [];

    /**
     * @var array
     */
    protected $casts = [];
}
