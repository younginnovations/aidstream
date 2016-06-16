<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Activity
 * @package App\Models\Activity
 */
class Activity extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = "activity_data";

    /**
     * Fillable property for mass assignment.
     * @var array
     */
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
        'policy_marker',
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
        'humanitarian_scope',
        'published_to_registry',
        'activity_workflow',
    ];

    /**
     * @var array
     */
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
        'policy_marker'              => 'json',
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
        'humanitarian_scope'         => 'json',
    ];

    /**
     * get activity identifier and title
     * @return string
     */
    public function getIdentifierTitleAttribute()
    {
        $identifier = $this->identifier['activity_identifier'];
        $title      = $this->title ? $this->title[0]['narrative'] : 'No Title';

        return $identifier.'('.$title.')';
    }

    /**
     * activity belongs to organization
     */
    public function organization()
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

    /**
     * An Activity has many ActivityResults.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(ActivityResult::class);
    }

    /**
     * Accessor for the updated_at attribute. Gets the updated_at attribute in the used format.
     * @param $date
     * @return bool|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    /**
     * An Activity has many ActivityDocumentLink.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentLinks()
    {
        return $this->hasMany(ActivityDocumentLink::class);
    }

    /** Returns total number of activities and last updated date of given organization
     * @param $orgId
     * @return mixed
     */
    public function getActivitiesData($orgId)
    {
        return DB::table('organizations')
                 ->join('activity_data', 'activity_data.organization_id', '=', 'organizations.id')
                 ->select(DB::raw('count(activity_data.id) as NoOfActivities, max(activity_data.updated_at) as updated_at'))
                 ->where('organizations.id', $orgId)
                 ->get();
    }
}
