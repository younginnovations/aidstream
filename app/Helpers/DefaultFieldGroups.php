<?php namespace App\Helpers;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;
use App\Models\Organization\Organization;
use App\Models\Settings;
use Illuminate\Support\Facades\Session;

/**
 * Class DefaultFieldGroups
 * @package App\Helpers
 */
class DefaultFieldGroups
{
    /**
     * return default field groups
     * @return mixed
     */
    public function get()
    {
        $settings           = Settings::where('organization_id', Session::get('org_id'))->first();
        $defaultFieldGroups = $settings->default_field_groups[0];

        $identification                      = isset($defaultFieldGroups['Identification']) ? $defaultFieldGroups['Identification'] : [];
        $defaultFieldGroup['Identification'] = array_merge(
            ['reporting_organization' => 'Reporting Organization', 'iati_identifier' => 'Activity Identifier'],
            $identification
        );
        $defaultFieldGroups                  = $defaultFieldGroup + $defaultFieldGroups;

        return $defaultFieldGroups;
    }

    /**
     * return data filled status on default field groups
     * @return array
     */
    public function getFilledStatus($id)
    {
        $activityData = $this->getActivityData($id);

        $filledStatus = [
            "Identification"              => [
                "reporting_organization" => $activityData['reporting_organization'],
                "iati_identifier"        => $activityData['identifier'],
                "other_identifier"       => $activityData['other_identifier']
            ],
            "Basic Activity Information"  => [
                "title"           => $activityData['title'],
                "description"     => $activityData['description'],
                "activity_status" => $activityData['activity_status'],
                "activity_date"   => $activityData['activity_date'],
                "contact_info"    => $activityData['contact_info'],
                "activity_scope"  => $activityData['activity_scope']
            ],
            "Participating Organizations" => [
                "participating_organization" => $activityData['participating_organization']
            ],
            "Geopolitical Information"    => [
                "recipient_country" => $activityData['recipient_country'],
                "recipient_region"  => $activityData['recipient_region'],
                "location"          => $activityData['location']
            ],
            "Classifications"             => [
                "sector"               => $activityData['sector'],
                "policy_maker"         => $activityData['policy_maker'],
                "collaboration_type"   => $activityData['collaboration_type'],
                "default_flow_type"    => $activityData['default_flow_type'],
                "default_finance_type" => $activityData['default_finance_type'],
                "default_aid_type"     => $activityData['default_aid_type'],
                "default_tied_status"  => $activityData['default_tied_status'],
                "country_budget_items" => $activityData['country_budget_items']
            ],
            "Financial"                   => [
                "budget"               => $activityData['budget'],
                "planned_disbursement" => $activityData['planned_disbursement'],
                "transaction"          => $activityData['transactions'],
                "capital_spend"        => $activityData['capital_spend']
            ],
            "Related Documents"           => [
                "document_link" => $activityData['document_link']
            ],
            "Relations"                   => [
                "related_activity" => $activityData['related_activity']
            ],
            "Performance"                 => [
                "condition"   => $activityData['conditions'],
                "result"      => $activityData['results'],
                "legacy_data" => $activityData['legacy_data']
            ]
        ];

        if (session('version') === "V202") {
            $filledStatus["Classifications"]["humanitarian_scope"] = $activityData['humanitarian_scope'];
        }

        return $filledStatus;
    }

    /**
     * return activity data with results and transactions
     * @param int $id
     */
    private function getActivityData($id)
    {
        $activityData                           = Activity::find($id)->toArray();
        $reportingOrg                           = Organization::find(Session::get('org_id'))->reporting_org;
        $results                                = ActivityResult::where('activity_id', $id)->get()->toArray();
        $transactions                           = Transaction::where('activity_id', $id)->get()->toArray();
        $activityData['results']                = $results;
        $activityData['transactions']           = $transactions;
        $activityData['reporting_organization'] = $reportingOrg;

        return $activityData;
    }
}
