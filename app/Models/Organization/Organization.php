<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {
    protected $table ="organizations";
    protected $fileable_key = "organization";
    protected $fillable     = ['identifier', 'reporting_org'];

    public function getOrgReportingOrg()
    {
        return json_decode($this->reporting_org);
        ;
    }

    public function buildOrgReportingOrg()
    {
        return json_decode($this->reporting_org,true);
        ;
    }
}
