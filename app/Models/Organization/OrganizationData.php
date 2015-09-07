<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationData extends Model {
    protected $table ="organization_data";
    protected $fileable_key = "organization";
    protected $fillable     = ['name', 'total_budget', 'recipient_org_budget', 'recipient_country_budget', 'document_link'];

    public function getName()
    {
        return json_decode($this->name);
        ;
    }
    public function buildOrgName()
    {
        return json_decode($this->name,true);
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
