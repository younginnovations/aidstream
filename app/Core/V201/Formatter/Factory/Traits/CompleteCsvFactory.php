<?php namespace App\Core\V201\Formatter\Factory\Traits;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class CompleteCsvFactory
 * @package App\Core\V201\Formatter\Factory
 */
trait CompleteCsvFactory
{
    use ElementDataPacker;

    /**
     * Factory method for reportingOrg Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function reportingOrg(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId            = $activity->id;
            $reportingOrganisation = $activity->organization->reporting_org;
            $data                  = $this->fillReportingOrgData($activityId, $data, $reportingOrganisation);
        }

        return $data;
    }

    /**
     * Factory method for Title Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function title(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $data       = $this->fillTitleData($activityId, $data, $activity['title']);
        }

        return $data;
    }

    /**
     * Factory method for Description Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function description(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId          = $activity->id;
            $activityDescription = $activity['description'];
            $data                = $this->fillDescriptionData($activityId, $data, $activityDescription);
        }

        return $data;
    }

    /**
     * Factory method for Participating Organization Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function participatingOrg(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId                = $activity->id;
            $participatingOrganization = $activity->participating_organization;
            $data                      = $this->fillParticipatingOrgData($activityId, $data, $participatingOrganization);
        }

        return $data;
    }

    /**
     * Factory method for Other Activity Identifier Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function otherActivityIdentifier(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $otherIdentifier = $activity->other_identifier;
            $data            = $this->fillOtherActivityIdentifierData($activityId, $data, $otherIdentifier);
        }

        return $data;
    }

    /**
     * Factory method for Activity Status Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityStatus(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_activitystatus_code'] = $activity->activity_status;
        }

        return $data;
    }

    /**
     * Factory method for Activity Date Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityDate(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId   = $activity->id;
            $activityDate = $activity->activity_date;
            $data         = $this->fillActivityDateData($activityId, $data, $activityDate);
        }

        return $data;
    }

    /**
     * Factory method for ContactInfo Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function contactInfo(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId  = $activity->id;
            $contactInfo = $activity->contact_info;
            $data        = $this->fillContactInfoData($activityId, $data, $contactInfo);
        }

        return $data;
    }

    /**
     * Factory method for Activity Scope Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityScope(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_activityscope_code'] = $activity->activity_scope;
        }

        return $data;
    }

    /**
     * Factory method for RecipientCountry Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function recipientCountry(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId       = $activity->id;
            $recipientCountry = $activity->recipient_country;
            $data             = $this->fillRecipientCountryData($activityId, $data, $recipientCountry);
        }

        return $data;
    }

    /**
     * Factory method for RecipientRegion Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function recipientRegion(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $recipientRegion = $activity->recipient_region;
            $data            = $this->fillRecipientRegionData($activityId, $data, $recipientRegion);
        }

        return $data;
    }

    /**
     * Factory method for Location Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function location(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $locations  = $activity->location;
            $data       = $this->fillLocationData($activityId, $data, $locations);
        }

        return $data;
    }

    /**
     * Factory method for Sector Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function sector(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $sectors    = $activity->sector;
            $data       = $this->fillSectorData($activityId, $data, $sectors);
        }

        return $data;
    }

    /**
     * Factory method for CountryBudgetItem Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function countryBudgetItems(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId         = $activity->id;
            $countryBudgetItems = $activity->country_budget_items;
            $data               = $this->fillCountryBudgetItemsData($activityId, $data, $countryBudgetItems);
        }

        return $data;
    }

    /**
     * Factory method for PolicyMarker Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function policyMarker(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId   = $activity->id;
            $policyMarker = $activity->policy_marker;
            $data         = $this->fillPolicyMarkerData($activityId, $data, $policyMarker);

        }

        return $data;
    }

    /**
     * Factory method for CollaborationType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function collaborationType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_collaborationtype_code'] = $activity->collaboration_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultFlowType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultFlowType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultflowtype_code'] = $activity->default_flow_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultFinanceType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultFinanceType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultfinancetype_code'] = $activity->default_finance_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultAidType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultAidType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultaidtype_code'] = $activity->default_aid_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultTiedStatus Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultTiedStatus(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaulttiedstatus_code'] = $activity->default_tied_status;
        }

        return $data;
    }

    /**
     * Factory method for Budget Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function budget(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $budget     = $activity->budget;
            $data       = $this->fillBudgetData($activityId, $data, $budget);
        }

        return $data;
    }

    /**
     * Factory method for PlannedDisbursement Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function plannedDisbursement(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId          = $activity->id;
            $plannedDisbursement = $activity->planned_disbursement;
            $data                = $this->fillPlannedDisbursementData($activityId, $data, $plannedDisbursement);
        }

        return $data;
    }

    /**
     * Factory method for CapitalSpend Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function capitalSpend(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_capitalspend_percentage'] = $activity->capital_spend;
        }

        return $data;
    }

    /**
     * Factory method for DocumentLink Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function documentLink(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId    = $activity->id;
            $documentLinks = $activity->document_link;
            $data          = $this->fillDocumentLinkData($activityId, $data, $documentLinks);
        }

        return $data;
    }

    /**
     * Factory method for RelatedActivity Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function relatedActivity(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $relatedActivity = $activity->related_activity;
            $data            = $this->fillRelatedActivityData($activityId, $data, $relatedActivity);
        }

        return $data;
    }

    /**
     * Factory method for RelatedActivity Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function legacyData(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $legacyData = $activity->legacy_data;
            $data       = $this->fillLegacyData($activityId, $data, $legacyData);
        }

        return $data;
    }

    /**
     * Factory method for Conditions Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function conditions(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $conditions = $activity->conditions;
            $data       = $this->fillConditionData($activityId, $data, $conditions);
        }

        return $data;
    }

    /**
     * Factory method for Humanitarian Scope Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function humanitarianScope(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId        = $activity->id;
            $humanitarianScope = $activity->humanitarian_scope;
            $data              = $this->fillHumanitarianScopeData($activityId, $data, $humanitarianScope);
        }

        return $data;
    }
}
