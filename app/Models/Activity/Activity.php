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
        'default_field_values',
        'humanitarian_scope'
    ];

    protected $casts = [
        'identifier'                 => 'json',
        'other_identifier'           => 'json',
        'title'                      => 'json',
        'description'                => 'json',
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
        'default_field_values'       => 'json',
        'humanitarian_scope'         => 'json'
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

    /**
     * @return array
     */
    public function getActivityDataListAttribute()
    {
        $activities = $this->toArray();
        foreach ($activities as $activityIndex => $activity) {
            !(null === $activities[$activityIndex]) ?: $activities[$activityIndex] = [];
        }

        return $activities;

    }

    /**
     * Activity has many transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Activity\Transaction', 'activity_id');
    }

    /**
     * get the transactions related to activity
     * @return array
     */
    public function getTransactions()
    {
        $transactions = [];
        foreach ($this->transactions as $transactionIndex => $transaction) {
            $transactions[$transactionIndex]       = $transaction->transaction;
            $transactions[$transactionIndex]['id'] = $transaction->id;
        }

        return $transactions;
    }

    /**
     * get activity identifier
     * @return mixed
     */
    public function getActivityIdentifierAttribute()
    {
        return $this->identifier['activity_identifier'];
    }
}
