<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Models\Activity
 */
class Activity extends Model
{
    protected $table = "activity_data";
    protected $fillable = [
        'identifier',
        'organization_id',
        'other_identifier',
        'title',
        'description',
        'activity_status',
        'activity_date',
        'contact_info',
        'activity_scope',
        'participating_organization',
        'recipient_country',
        'recipient_region',
        'location',
        'sector',
        'country_budget_items',
        'policy_maker',
        'collaboration_type',
        'default_flow_type',
        'default_finance_type',
        'default_aid_type',
        'default_tied_status',
        'budget',
        'planned_disbursement',
        'capital_spend',
        'document_link',
        'related_activity',
        'legacy_data',
        'conditions',
        'result'
    ];

    protected $casts = [
        'identifier'                 => 'json',
        'other_identifier'           => 'json',
        'title'                      => 'json',
        'description'                => 'json',
        'activity_status'            => 'json',
        'activity_date'              => 'json',
        'contact_info'               => 'json',
        'activity_scope'             => 'json',
        'participating_organization' => 'json',
        'recipient_country'          => 'json',
        'recipient_region'           => 'json',
        'location'                   => 'json',
        'sector'                     => 'json',
        'country_budget_items'       => 'json',
        'policy_maker'               => 'json',
        'collaboration_type'         => 'json',
        'default_flow_type'          => 'json',
        'default_finance_type'       => 'json',
        'default_aid_type'           => 'json',
        'default_tied_status'        => 'json',
        'budget'                     => 'json',
        'planned_disbursement'       => 'json',
        'capital_spend'              => 'json',
        'document_link'              => 'json',
        'related_activity'           => 'json',
        'legacy_data'                => 'json',
        'conditions'                 => 'json',
        'result'                     => 'json'
    ];

    /**
     * get activity identifier and title
     * @return string
     */
    public function getIdentifierTitleAttribute()
    {
        $identifier = $this->identifier['activity_identifier'];
        $title      = $this->title ? $this->title[0]['narrative'] : 'No Title';

        return $identifier . '(' . $title . ')';
    }

    /**
     * activity belongs to organization
     */
    protected function organization()
    {
        return $this->belongsTo('App\Models\Organization\Organization', 'organization_id');
    }

}
