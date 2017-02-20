<?php

$router->group(
    ['namespace' => 'Complete\Activity\Import', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get(
            '/import-activity/upload-csv',
            [
                'as'   => 'activity.upload-csv',
                'uses' => 'ImportController@uploadActivityCsv'
            ]
        );

        $router->post(
            '/import-activity/import-csv',
            [
                'as'   => 'activity.import-csv',
                'uses' => 'ImportController@activities'
            ]
        );

        $router->get(
            'import-activity/download-activity-template',
            [
                'as'   => 'activity.download-template',
                'uses' => 'ImportController@downloadActivityTemplate'
            ]
        );

        $router->get(
            '/import-activity/get-valid-data',
            [
                'as'   => 'activity.get-valid-data',
                'uses' => 'ImportController@getValidData'
            ]
        );

        $router->get(
            '/import-activity/get-invalid-data',
            [
                'as'   => 'activity.get-invalid-data',
                'uses' => 'ImportController@getInvalidData'
            ]
        );

        $router->post(
            '/import-activity/import-validated-activities',
            [
                'as'   => 'activity.import-validated-activities',
                'uses' => 'ImportController@importValidatedActivities'
            ]
        );

        $router->get(
            '/import-activity/import-status',
            [
                'as'   => 'activity.import-status',
                'uses' => 'ImportController@status'
            ]
        );

        $router->get(
            '/import-activity/check-status',
            [
                'as'   => 'activity.check-status',
                'uses' => 'ImportController@checkStatus'
            ]
        );

        $router->get(
            '/import-activity/remaining-invalid-data',
            [
                'as'   => 'activity.remaining-invalid-data',
                'uses' => 'ImportController@getRemainingInvalidData'
            ]
        );

        $router->get(
            '/import-activity/remaining-valid-data',
            [
                'as'   => 'activity.remaining-valid-data',
                'uses' => 'ImportController@getRemainingValidData'
            ]
        );

        $router->get(
            '/import-activity/clear-invalid-activities',
            [
                'as'   => 'activity.clear-invalid-activities',
                'uses' => 'ImportController@clearInvalidActivities'
            ]
        );

        $router->get(
            '/check-session-status',
            [
                'as'   => 'activity.check-session-status',
                'uses' => 'ImportController@checkSessionStatus'
            ]
        );

        $router->post(
            '/import-activity/cancel-import',
            [
                'as'   => 'activity.cancel-import',
                'uses' => 'ImportController@cancel'
            ]
        );

        $router->get(
            '/import-activity/get-data',
            [
                'as'   => 'activity.get-data',
                'uses' => 'ImportController@getData'
            ]
        );

        $router->get(
            '/import-activity/upload-csv-redirect',
            [
                'as'   => 'activity.upload-redirect',
                'uses' => 'ImportController@uploadRedirect'
            ]
        );

        $router->get(
            '/import-activity/localisedText',
            [
                'as'   => 'activity.localisedText',
                'uses' => 'ImportController@getLocalisedCsvFile'
            ]
        );
    }
);
