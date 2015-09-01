<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {
    protected $table ="organizations";
    protected $fileable_key = "organization";
    protected $fillable     = ['identifier', 'name', 'reporting_org', 'total_budget', 'recipient_org_budget', 'recipient_country_budget', 'document_link'];

    public function buildOrganizationName()
    {
        return json_decode($this->name,true);
        ;
    }

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

    public function getTotalBudget()
    {
        return json_decode($this->total_budget);
        ;
    }

    public function buildTotalBudget()
    {
        return json_decode($this->total_budget, true);
        ;
    }

    public function getRecipientOrgBudget()
    {
        return json_decode($this->recipient_org_budget);
        ;
    }

    public function buildRecipientOrgBudget()
    {
        return json_decode($this->recipient_org_budget, true);
        ;
    }

    public function getRecipientCountryBudget()
    {
        return json_decode($this->recipient_country_budget);
        ;
    }

    public function buildRecipientCountryBudget()
    {
        return json_decode($this->recipient_country_budget, true);
        ;
    }

    public function getDocumentLink()
    {
        return json_decode($this->document_link);
        ;
    }

    public function buildDocumentLink()
    {
        return json_decode($this->document_link, true);
        ;
    }


}
