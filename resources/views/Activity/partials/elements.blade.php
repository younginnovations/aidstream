<div class="panel panel-default panel-element-detail element-show">
    <div class="panel-body">
        {{--*/
        $reportingOrganization = $activityDataList['reporting_org'][0];
        $identifier = $activityDataList['identifier'];
        $otherIdentifiers = $activityDataList['other_identifier'];
        $titles = $activityDataList['title'];
        $titlesExceptFirstElement = array_slice($titles , 1);
        $descriptions = $activityDataList['description'];
        $activityStatus = $activityDataList['activity_status'];
        $activityDates = $activityDataList['activity_date'];
        $activityScope = $activityDataList['activity_scope'];
        $contactInfo = $activityDataList['contact_info'];
        $participatingOrganizations = $activityDataList['participating_organization'];
        $recipientCountries = $activityDataList['recipient_country'];
        $recipientRegions = $activityDataList['recipient_region'];
        $locations = $activityDataList['location'];
        $sectors = $activityDataList['sector'];
        $countryBudgetItems = $activityDataList['country_budget_items'];
        $policyMarkers = $activityDataList['policy_marker'];
        $collaborationType = $activityDataList['collaboration_type'];
        $defaultFlowType = $activityDataList['default_flow_type'];
        $defaultFinanceType = $activityDataList['default_finance_type'];
        $defaultAidType = $activityDataList['default_aid_type'];
        $defaultTiedStatus = $activityDataList['default_tied_status'];
        $budgets = $activityDataList['budget'];
        $plannedDisbursements = $activityDataList['planned_disbursement'];
        $documentLinks = $activityDataList['document_links'];
        $relatedActivities = $activityDataList['related_activity'];
        $legacyDatas = $activityDataList['legacy_data'];
        $conditions = $activityDataList['conditions'];
        $results = $activityDataList['results'];
        $transactions = $activityDataList['transaction'];
        $capitalSpend = $activityDataList['capital_spend'];
        $humanitarianScopes = $activityDataList['humanitarian_scope'];
        /*--}}
        @include('Activity.partials.reportingOrganization')
        @include('Activity.partials.identifier')
        @include('Activity.partials.otherIdentifier')
        @include('Activity.partials.title')
        @include('Activity.partials.description')
        @include('Activity.partials.activityStatus')
        @include('Activity.partials.activityDate')
        @include('Activity.partials.contactInfo')
        @include('Activity.partials.activityScope')
        @include('Activity.partials.participatingOrganization')
        @include('Activity.partials.recipientCountry')
        @include('Activity.partials.recipientRegion')
        @include('Activity.partials.location')
        @include('Activity.partials.sector')
        @include('Activity.partials.policyMarker')
        @include('Activity.partials.collaborationType')
        @include('Activity.partials.defaultFlowType')
        @include('Activity.partials.defaultFinanceType')
        @include('Activity.partials.defaultAidType')
        @include('Activity.partials.defaultTiedStatus')
        @include('Activity.partials.countryBudgetItem')
        @if(!empty($humanitarianScopes))
            @include('Activity.partials.humanitarianScope')
        @endif
        @include('Activity.partials.budget')
        @include('Activity.partials.plannedDisbursement')
        @include('Activity.partials.transaction')
        @include('Activity.partials.capitalSpend')
        @include('Activity.partials.documentLink')
        @include('Activity.partials.relatedActivity')
        @include('Activity.partials.condition')
        @include('Activity.partials.result')
        @include('Activity.partials.legacyData')
    </div>
</div>