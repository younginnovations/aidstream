<?php namespace App\Core\V201\Repositories;

use App\Models\Organization\OrganizationData;
use App\Models\Activity\Activity;
use App\Models\Settings;
use Illuminate\Support\Collection;

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
     * @param Settings         $settings
     * @param OrganizationData $orgData
     * @param Activity         $activity
     */
    function __construct(Settings $settings, OrganizationData $orgData, Activity $activity)
    {
        $this->settings = $settings;
        $this->orgData  = $orgData;
        $this->activity = $activity;
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
            $budgets = (array) $activity->budget;

            foreach ($budgets as $budgetIndex => $budget) {
                $budgets[$budgetIndex]['status'] = "1";
            }

            (!$budgets) ?: $activity->budget = $budgets;
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
}
