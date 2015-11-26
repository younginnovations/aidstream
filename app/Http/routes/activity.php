<?php

$router->group(
    ['namespace' => 'Complete\Activity'],
    function ($router) {
        $router->resource('activity', 'ActivityController');
        $router->resource('activity.reporting-organization', 'ReportingOrganization');
        $router->resource('activity.iati-identifier', 'IatiIdentifierController');
        $router->resource('activity.other-identifier', 'OtherIdentifierController');
        $router->resource('activity.title', 'TitleController');
        $router->resource('activity.description', 'DescriptionController');
        $router->resource('activity.activity-status', 'ActivityStatusController');
        $router->resource('activity.activity-date', 'ActivityDateController');
        $router->resource('activity.contact-info', 'ContactInfoController');
        $router->resource('activity.activity-scope', 'ActivityScopeController');
        $router->resource('activity.participating-organization', 'ParticipatingOrganizationController');
        $router->resource('activity.recipient-country', 'RecipientCountryController');
        $router->resource('activity.recipient-region', 'RecipientRegionController');
        $router->resource('activity.sector', 'SectorController');
        $router->resource('activity.country-budget-items', 'CountryBudgetItemController');
        $router->resource('activity.location', 'LocationController');
        $router->resource('activity.budget', 'BudgetController');
        $router->resource('activity.policy-maker', 'PolicyMakerController');
        $router->resource('activity.collaboration-type', 'CollaborationTypeController');
        $router->resource('activity.default-flow-type', 'DefaultFlowTypeController');
        $router->resource('activity.default-finance-type', 'DefaultFinanceTypeController');
        $router->resource('activity.default-aid-type', 'DefaultAidTypeController');
        $router->resource('activity.default-tied-status', 'DefaultTiedStatusController');
        $router->resource('activity.capital-spend', 'CapitalSpendController');
        $router->resource('activity.condition', 'ConditionController');
        $router->resource('activity.planned-disbursement', 'PlannedDisbursementController');
        $router->resource('activity.document-link', 'DocumentLinkController');
        $router->resource('activity.related-activity', 'RelatedActivityController');
        $router->resource('activity.transaction', 'TransactionController');
        $router->resource('activity.transaction-upload', 'TransactionUploadController');
        $router->resource('activity.legacy-data', 'LegacyDataController');
    }
);
