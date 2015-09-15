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

    public function buildOrganizationRegistryInfo()
    {
        return json_decode($this->registry_info, true);
    }

    public function buildOrganizationDefaultFieldValues()
    {
        return json_decode($this->default_field_values, true);
    }

    public function buildOrganizationDefaultFieldGroups()
    {
        return json_decode($this->default_field_groups, true);
    }
}
