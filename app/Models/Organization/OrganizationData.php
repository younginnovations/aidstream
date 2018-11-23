<?php namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationData extends Model
{
    protected $table = "organization_data";
    protected $fillable = [
        'name',
        'total_budget',
        'recipient_organization_budget',
        'recipient_region_budget',
        'recipient_country_budget',
        'document_link',
        'organization_id',
        'status',
        'total_expenditure',
        'type',
        'country',
        'is_reporting_org',
        'is_publisher',
        'identifier',
        'used_by',
        'org_data'
    ];

    protected $casts = [
        'name'                          => 'json',
        'total_budget'                  => 'json',
        'recipient_organization_budget' => 'json',
        'recipient_region_budget'       => 'json',
        'recipient_country_budget'      => 'json',
        'document_link'                 => 'json',
        'total_expenditure'             => 'json',
        'used_by'                       => 'json',
        'org_data'                      => 'json'
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

    /**
     * OrganizationData belongs to an Organization.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the number of Activities in which an Organization is selected as a Partner.
     *
     * @return string
     */
    public function includedActivities()
    {
        return sprintf("<span>%s</span> Activities", count($this->used_by));
    }

    /**
     * Get the status of the Organization Data.
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getStatus()
    {
        switch ($this->status) {
            case 0:
                return trans('global.draft');
            case 1:
                return trans('global.completed');
            case 2:
                return trans('global.verified');
            case 3:
                return trans('global.published');
        }
    }

    /**
     * Get the Reporting Organization associated with an OrganizationData.
     *
     * @return mixed
     */
    public function getReportingOrgAttribute()
    {
        return array_get($this->organization->reporting_org, '0.narrative.0.narrative');
    }

    /**
     * Get the Organization Identifier Attribute.
     *
     * @return mixed
     */
    public function getOrganizationIdentifierAttribute()
    {
        return array_get($this->organization->reporting_org, '0.reporting_organization_identifier');
    }

    /**
     * Get the Name of the Organization.
     *
     * @return mixed|null
     */
    public function actualName()
    {
        if ($this->name && is_array($this->name)) {
            return array_get($this->name, '0.narrative', null);
        }

        return null;
    }
}
