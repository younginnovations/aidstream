<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class ActivityLocation extends Model
{
    protected $table = 'activity_location';

    protected $fillable = [
        'activity_id',
        'municipality_id',
        'ward'
    ];

}
