<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class ActivityInRegistry extends Model
{
    protected $table = "activities_in_registry";
    protected $fillable = ['organization_id', 'activity_id', 'activity_data'];

    protected $casts = [
        'activity_data' => 'json'
    ];
}
