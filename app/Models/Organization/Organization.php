<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table    = "organizations";
    protected $fillable = ['name', 'address', 'user_identifier', 'reporting_org'];
    protected $casts    = ['reporting_org' => 'json'];
}
