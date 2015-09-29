<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = "activity_data";
    protected $fillable = [
        'identifier',
        'organization_id',
        'other_identifier',
        'title'
    ];

    protected $casts = [
        'identifier'       => 'json',
        'other_identifier' => 'json',
        'title'            => 'json',
    ];

}
