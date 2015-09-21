<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "settings";
    protected $fileable_key = "settings";
    protected $fillable = [
        'publishing_type',
        'registry_info',
        'default_field_values',
        'default_field_groups',
        'version',
        'organization_id',
        'status'
    ];
    protected $casts = [
        'registry_info'             => 'json',
        'default_field_values'      => 'json',
        'default_field_groups'      => 'json',
    ];

}
