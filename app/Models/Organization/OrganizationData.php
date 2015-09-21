<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationData extends Model
{
    protected $table = "organization_data";
    protected $fillable = [
        'name',
        'total_budget',
        'recipient_organization_budget',
        'recipient_country_budget',
        'document_link',
        'organization_id',
        'status'
    ];
    protected $casts = [
        'name'                          => 'json',
        'total_budget'                  => 'json',
        'recipient_organization_budget' => 'json',
        'recipient_country_budget'      => 'json',
        'document_link'                 => 'json'
    ];

    public function getName()
    {
        return $this->name;
    }

    public function buildOrgName()
    {
        return json_decode($this->name, true);
    }

    public function getTotalBudget()
    {
        return $this->total_budget;
    }

    public function buildTotalBudget()
    {
        return json_decode($this->total_budget, true);
    }

    public function getRecipientOrgBudget()
    {
        return $this->recipient_organization_budget;
    }

    public function buildRecipientOrgBudget()
    {
        return $this->recipient_organization_budget;
    }

    public function getRecipientCountryBudget()
    {
        return $this->recipient_country_budget;
    }

    public function buildRecipientCountryBudget()
    {
        return json_decode($this->recipient_country_budget, true);
    }

    public function getDocumentLink()
    {
        return $this->document_link;
    }

    public function buildDocumentLink()
    {
        return json_decode($this->document_link, true);
    }


}
