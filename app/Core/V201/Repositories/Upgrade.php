<?php namespace App\Core\V201\Repositories;

use App\Models\Organization\OrganizationData;
use App\Models\Activity\Activity;
use App\Models\Settings;
use Illuminate\Support\Collection;
use App\Models\Activity\Transaction;

/**
 * Class Upgrade
 * @package App\Core\V201\Repositories
 */
class Upgrade
{
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var OrganizationData
     */
    protected $orgData;
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var Transaction
     */

    /**
     * @param Settings         $settings
     * @param OrganizationData $orgData
     * @param Activity         $activity
     * @param Transaction      $transaction
     */
    function __construct(Settings $settings, OrganizationData $orgData, Activity $activity, Transaction $transaction)
    {
        $this->settings    = $settings;
        $this->orgData     = $orgData;
        $this->activity    = $activity;
        $this->transaction = $transaction;
    }

    /**
     * @param $organization_id
     * @return mixed
     */
    protected function getSettings($organization_id)
    {
        return $this->settings->where('organization_id', $organization_id)->first();
    }

    /**
     * @param $organization_id
     * @return mixed
     */
    protected function getOrganizationData($organization_id)
    {
        return $this->orgData->where('organization_id', $organization_id)->first();
    }

    /**
     * @param $organization_id
     * @return mixed
     */
    protected function getActivities($organization_id)
    {
        return $this->activity->where('organization_id', $organization_id)->get();
    }

    /**
     * @param $orgId
     * @param $version
     */
    public function upgrade($orgId, $version)
    {
        $settings         = $this->getSettings($orgId);
        $organizationData = $this->getOrganizationData($orgId);
        $activities       = $this->getActivities($orgId);
        $this->upgradeSettings($settings);
        $this->upgradeOrganizationData($organizationData);
        $this->upgradeActivities($activities);
        $this->updateVersion($orgId, $version);
        $this->upgradeActivityTransaction($activities);
    }

    /**
     * @param Settings $settings
     */
    protected function upgradeSettings(Settings $settings)
    {
        $defaultFieldValues = (array) $settings->default_field_values;

        foreach ($defaultFieldValues as $defaultFieldValueIndex => $defaultFieldValue) {
            $defaultFieldValues[$defaultFieldValueIndex]['humanitarian'] = "0";
        }

        (!$defaultFieldValues) ?: $settings->default_field_values = $defaultFieldValues;
        $settings->save();
    }

    /**
     * @param OrganizationData $organizationData
     */
    protected function upgradeOrganizationData(OrganizationData $organizationData)
    {
        $totalBudgets        = (array) $organizationData->total_budget;
        $recipientOrgBudgets = (array) $organizationData->recipient_organization_budget;
        $documentLinks       = (array) $organizationData->document_link;

        foreach ($totalBudgets as $totalBudgetIndex => $totalBudget) {
            $totalBudgets[$totalBudgetIndex]['status'] = "1";
        }

        foreach ($recipientOrgBudgets as $recipientOrgBudgetIndex => $recipientOrgBudget) {
            $recipientOrgBudgets[$recipientOrgBudgetIndex]['status'] = "1";
        }

        foreach ($documentLinks as $documentLinkIndex => $documentLink) {
            $documentLinks[$documentLinkIndex]['document_date'][0]['date'] = "";
        }

        (!$totalBudgets) ?: $organizationData->total_budget = $totalBudgets;
        (!$recipientOrgBudgets) ?: $organizationData->recipient_organization_budget = $recipientOrgBudgets;
        (!$documentLinks) ?: $organizationData->document_link = $documentLinks;
        $organizationData->save();
    }

    /**
     * @param Collection $activities
     */
    protected function upgradeActivities(Collection $activities)
    {
        foreach ($activities as $activity) {
            $budgets            = (array) $activity->budget;
            $defaultFieldValues = (array) $activity->default_field_values;
            $recipientRegions   = (array) $activity->recipient_region;
            $sectors            = (array) $activity->sector;
            $policyMarkers      = (array) $activity->policy_marker;

            foreach ($recipientRegions as $recipientIndex => $recipientRegion) {
                $recipientRegions[$recipientIndex]['vocabulary_uri'] = "";
            }

            foreach ($budgets as $budgetIndex => $budget) {
                $budgets[$budgetIndex]['status'] = "1";
            }

            foreach ($defaultFieldValues as $defaultFieldValueIndex => $defaultFieldValue) {
                $defaultFieldValues[$defaultFieldValueIndex]['humanitarian'] = "0";
            }

            foreach ($policyMarkers as $policyMarkerIndex => $policyMarker) {
                $policyMarkers[$policyMarkerIndex]['vocabulary_uri'] = "";
            }

            foreach ($sectors as $sectorIndex => $sector) {
                $sectors[$sectorIndex]['vocabulary_uri'] = "";
            }

            (!$budgets) ?: $activity->budget = $budgets;
            (!$defaultFieldValues) ?: $activity->default_field_values = $defaultFieldValues;
            (!$recipientRegions) ?: $activity->recipient_region = $recipientRegions;
            (!$sectors) ?: $activity->sector = $sectors;
            (!$policyMarkers) ?: $activity->policy_marker = $policyMarkers;
            $activity->save();
        }
    }

    /**
     * @param $orgId
     * @param $version
     */
    protected function updateVersion($orgId, $version)
    {
        $settings          = $this->getSettings($orgId);
        $settings->version = $version;
        $settings->save();
        session()->put('version', 'V' . str_replace('.', '', $version));
    }

    /**
     * @param $activities
     */
    protected function upgradeActivityTransaction(Collection $activities)
    {
        foreach ($activities as $activity) {
            $transactions = $this->getTransactions($activity->id);
            foreach ($transactions as $eachTransaction) {
                $transactionField = $eachTransaction->transaction;
                $sectors          = $transactionField['sector'];
                $transactionField['sector'][$sectorIndex]['vocabulary_uri'] = "";                

                $regions = $transactionField['recipient_region'];
                foreach ($regions as $regionIndex => $region) {
                    $transactionField['recipient_region'][$regionIndex]['vocabulary_uri'] = "";
                }

                $eachTransaction->transaction = $transactionField;
                $eachTransaction->save();
            }
        }
    }

    /**
     * @param $activity_id
     * @return mixed
     */
    protected function getTransactions($activity_id)
    {
        return $this->transaction->where('activity_id', $activity_id)->get();
    }
}
