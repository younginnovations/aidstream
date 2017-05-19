<?php

$router->group(
    ['namespace' => 'Complete\Activity', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->resource('activity', 'ActivityController');
        $router->resource('activity.delete', 'ActivityController@destroy');
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
        $router->resource('activity.policy-marker', 'PolicyMarkerController');
        $router->resource('activity.collaboration-type', 'CollaborationTypeController');
        $router->resource('activity.default-flow-type', 'DefaultFlowTypeController');
        $router->resource('activity.default-finance-type', 'DefaultFinanceTypeController');
        $router->resource('activity.default-aid-type', 'DefaultAidTypeController');
        $router->resource('activity.default-tied-status', 'DefaultTiedStatusController');
        $router->resource('activity.capital-spend', 'CapitalSpendController');
        $router->resource('activity.result', 'ResultController');
        $router->resource('activity.result.delete', 'ResultController@destroy');
        $router->resource('activity.condition', 'ConditionController');
        $router->resource('activity.planned-disbursement', 'PlannedDisbursementController');
        $router->resource('activity.document-link', 'DocumentLinkController');
        $router->resource('activity.document-link.delete', 'DocumentLinkController@destroy');
        $router->resource('activity.related-activity', 'RelatedActivityController');
        $router->resource('activity.transaction', 'TransactionController');
        $router->resource('activity.transaction.delete', 'TransactionController@destroy');
        $router->resource('activity.transaction-upload', 'TransactionUploadController');
        $router->resource('activity-upload', 'ActivityUploadController');
        $router->resource('activity.legacy-data', 'LegacyDataController');
        $router->resource('activity.humanitarian-scope', 'HumanitarianScopeController');
        $router->post('activity/{id}/update-status', 'ActivityController@updateStatus');
        $router->get(
            'delete-published-file/{id}',
            [
                'as'   => 'delete-published-file',
                'uses' => 'ActivityController@deletePublishedFile'
            ]
        );
        $router->get(
            'change-activity-default/{id}',
            [
                'as'   => 'change-activity-default',
                'uses' => 'ActivityController@changeActivityDefault'
            ]
        );
        $router->put(
            'update-activity-default/{id}',
            [
                'as'   => 'update-activity-default',
                'uses' => 'ActivityController@updateActivityDefault'
            ]
        );
        $router->resource('activity.humanitarian-scope', 'HumanitarianScopeController');
        $router->get(
            'activity/duplicate/{id}',
            [
                'as'   => 'activity.duplicate',
                'uses' => 'ActivityController@duplicateActivity'
            ]
        );
        $router->post(
            'activity/duplicate/{id}',
            [
                'as'   => 'activity.duplicate',
                'uses' => 'ActivityController@duplicateActivityAction'
            ]
        );

        $router->get(
            'activity/{id}/delete-element/{element}',
            [
                'as'   => 'activity.delete-element',
                'uses' => 'ActivityController@deleteElement'
            ]
        );
        $router->get(
            'activity/{id}/transaction/{transactionId}/{jsonPath}',
            [
                'as'   => 'activity.transaction.delete-block',
                'uses' => 'TransactionController@deleteBlock'
            ]
        )->where(['jsonPath' => '[a-z0-9_/]+']);

//        $router->get(
//            '/tweet',
//            [
//                'as'   => 'twitter',
//                'uses' => 'ActivityController@twitterPost'
//            ]
//        );

        $router->get(
            '/import-activity',
            [
                'as'   => 'import-activity.index',
                'uses' => 'ImportActivityController@index'
            ]
        );

        $router->match(
            ['get', 'post'],
            '/import-activity/list-activities',
            [
                'as'   => 'import-activity.list-activities',
                'uses' => 'ImportActivityController@listActivities'
            ]
        );

        $router->post(
            '/import-activity/import-activities',
            [
                'as'   => 'import-activity.import',
                'uses' => 'ImportActivityController@importActivities'
            ]
        );

        $router->get(
            '/activity/{activityId}/xml/view',
            [
                'as'   => 'view.activityXml',
                'uses' => 'ActivityController@viewActivityXml'
            ]
        );

        $router->get(
            '/activity/{activityId}/xml/view/{true}',
            [
                'as'   => 'errors.activityXml',
                'uses' => 'ActivityController@viewActivityXml'
            ]
        );

        $router->get(
            '/activity/{activity}/xml/download',
            [
                'as'   => 'download.activityXml',
                'uses' => 'ActivityController@downloadActivityXml'
            ]
        );

        $router->get(
            '/activity/{activityId}/removeActivitySector',
            [
                'as'   => 'remove.activitySector',
                'uses' => 'ActivityController@removeActivitySector'
            ]
        );

        $router->get(
            '/activity/{activityId}/removeTransactionSector',
            [
                'as'   => 'remove.transactionSector',
                'uses' => 'ActivityController@removeTransactionSector'
            ]
        );

        $router->post(
            '/activity/getTransactionView',
            [
                'as'   => 'activity.getTransactionView',
                'uses' => 'ActivityController@getTransactionView'
            ]
        );

        $router->post(
            '/activity/getResultView',
            [
                'as'   => 'activity.getResultView',
                'uses' => 'ActivityController@getResultView'
            ]
        );
    }
);
