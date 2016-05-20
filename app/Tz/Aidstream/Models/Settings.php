<?php namespace App\Tz\Aidstream\Models;

use App\Models\Settings as AidstreamSettings;

/**
 * Class Settings
 * @package App\Tz\Aidstream\Models
 */
class Settings extends AidstreamSettings
{
    /**
     * @var string
     */
    protected $table = "settings";

    /**
     * Fillable property for mass assignment.
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
    protected $template = [
        'registry_info'        => ['publisher_id' => '', 'api_id' => '', 'publish_files' => ''],
        'default_field_values' => ['default_currency' => '', 'default_language' => '']
    ];
}
