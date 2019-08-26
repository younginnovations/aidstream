<?php namespace App\Core\V202\Repositories;

use App\Models\Organization\OrganizationData;
use App\Models\Activity\Activity;
use App\Models\Settings;
use Illuminate\Support\Collection;
use App\Models\Activity\Transaction;

/**
 * Class Upgrade
 * @package App\Core\V202\Repositories
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
        // $this->upgradeSettings($settings);
        // $this->upgradeOrganizationData($organizationData);
        $this->upgradeActivities($activities);
        $this->upgradeActivityTransaction($activities);
        $this->updateVersion($orgId, $version);
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

            $defaultAidType     = $activity->default_aid_type;

            $defaultAidTypeData = [
                "default_aidtype_vocabulary" => $defaultAidType ? 1 : '',
                "default_aid_type"           => (!is_array($defaultAidType)) ? $defaultAidType : '',
                "aidtype_earmarking_category" => '',
                "default_aid_type_text" => '',
                "cash_and_voucher_modalities" => ''
            ];

            $defaultAidTypeArray = [$defaultAidTypeData];

            (!$defaultAidType) ?: $activity->default_aid_type = $defaultAidTypeArray;
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

                $default_aid_type = getVal($transactionField, ['aid_type', 0, 'aid_type'], null);
                $default_aid_type = (!is_array($default_aid_type)) ? $default_aid_type : '';
                $defaultAidTypeData = [
                    "default_aidtype_vocabulary" => $default_aid_type ? 1 : '',
                    "default_aid_type"           => $default_aid_type ? $default_aid_type : '',
                    "aidtype_earmarking_category" => '',
                    "default_aid_type_text" => ''
                ];
                $defaultAidTypeArray = [$defaultAidTypeData];
                $transactionField['aid_type'][0]['aid_type'] = $defaultAidTypeArray;

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
