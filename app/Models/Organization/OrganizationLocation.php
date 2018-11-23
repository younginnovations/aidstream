<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationLocation extends Model
{
    protected $table = "organization_location";

    protected $fillable = [
        'organization_id',
        'district_id',
        'municipality_id'
    ];
}
