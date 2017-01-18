<div class="panel panel-default panel-element-detail element-show">
    <div class="panel-body">
        {{--*/
        $reportingOrganization = getVal($activityDataList, ['reporting_org', 0], []);
        $identifier = getVal($activityDataList, ['identifier'], []);
        $otherIdentifiers = getVal($activityDataList, ['other_identifier'], []);
        $titles = getVal($activityDataList, ['title'], []);
        $titlesExceptFirstElement = array_slice($titles , 1);
        $descriptions = getVal($activityDataList, ['description'], []);
        $activityStatus = getVal($activityDataList, ['activity_status'], []);
        $activityDates = getVal($activityDataList, ['activity_date'], []);
        $activityScope = getVal($activityDataList, ['activity_scope'], []);
        $contactInfo = getVal($activityDataList, ['contact_info'], []);
        $participatingOrganizations = getVal($activityDataList, ['participating_organization'], []);
        $recipientCountries = getVal($activityDataList, ['recipient_country'], []);
        $recipientRegions = getVal($activityDataList, ['recipient_region'], []);
        $locations = getVal($activityDataList, ['location'], []);
        $sectors = getVal($activityDataList, ['sector'], []);
        $countryBudgetItems = getVal($activityDataList, ['country_budget_items'], []);
        $policyMarkers = getVal($activityDataList, ['policy_marker'], []);
        $collaborationType = getVal($activityDataList, ['collaboration_type'], []);
        $defaultFlowType = getVal($activityDataList, ['default_flow_type'], []);
        $defaultFinanceType = getVal($activityDataList, ['default_finance_type'], []);
        $defaultAidType = getVal($activityDataList, ['default_aid_type'], []);
        $defaultTiedStatus = getVal($activityDataList, ['default_tied_status'], []);
        $budgets = getVal($activityDataList, ['budget'], []);
        $plannedDisbursements = getVal($activityDataList, ['planned_disbursement'], []);
        $documentLinks = getVal($activityDataList, ['document_links'], []);
        $relatedActivities = getVal($activityDataList, ['related_activity'], []);
        $legacyDatas = getVal($activityDataList, ['legacy_data'], []);
        $conditions = getVal($activityDataList, ['conditions'], []);
        $results = getVal($activityDataList, ['results'], []);
        $transactions = getVal($activityDataList, ['transaction'], []);
        $capitalSpend = getVal($activityDataList, ['capital_spend'], []);
        $humanitarianScopes = getVal($activityDataList, ['humanitarian_scope'], []);
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