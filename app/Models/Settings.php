<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings
 * @package App\Models
 */
class Settings extends Model
{
    /**
     * @var string
     */
    protected $table = "settings";

    /**
     * @var string
     */
    protected $fileable_key = "settings";

    /**
     * @var array
     */
    protected $fillable = [
        'publishing_type',
        'registry_info',
        'default_field_values',
        'default_field_groups',
        'version',
        'organization_id',
        'status'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'registry_info'        => 'json',
        'default_field_values' => 'json',
        'default_field_groups' => 'json',
    ];

}
