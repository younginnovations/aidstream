<?php

$router->group(
    ['namespace' => 'Complete\Activity\Import\Result', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get('/activity/{activity}/import-result/upload-csv', [
            'as'   => 'activity.result.upload-csv',
            'uses' => 'ImportResultController@uploadResultCsv'
        ]);

        $router->post('/activity/{activity}/import-result/import-csv', [
            'as'   => 'activity.result.import-csv',
            'uses' => 'ImportResultController@results'
        ]);

        $router->get('/activity/{activity}/import-result/download-result-template', [
            'as'   => 'activity.result.download-template',
            'uses' => 'ImportResultController@downloadResultTemplate'
        ]);

        $router->get('/activity/{activity}/import-result/get-valid-data', [
            'as'   => 'activity.result.get-valid-data',
            'uses' => 'ImportResultController@getValidData'
        ]);

        $router->get('/activity/{activity}/import-result/get-invalid-data', [
            'as'   => 'activity.result.get-invalid-data',
            'uses' => 'ImportResultController@getInvalidData'
        ]);

        $router->post('/activity/{activity}/import-result/import-validated-results', [
            'as'   => 'activity.result.import-validated-results',
            'uses' => 'ImportResultController@importValidatedResults'
        ]);

        $router->get('/activity/{activity}/import-result/import-status', [
            'as'   => 'activity.result.import-status',
            'uses' => 'ImportResultController@status'
        ]);

        $router->get('/activity/{activity}/import-result/check-status', [
            'as'   => 'activity.result.check-status',
            'uses' => 'ImportResultController@checkStatus'
        ]);

        $router->get('/activity/{activity}/import-result/remaining-invalid-data', [
            'as'   => 'activity.result.remaining-invalid-data',
            'uses' => 'ImportResultController@getRemainingInvalidData'
        ]);

        $router->get('/activity/{activity}/import-result/remaining-valid-data', [
            'as'   => 'activity.result.remaining-valid-data',
            'uses' => 'ImportResultController@getRemainingValidData'
        ]);

        $router->get('/activity/{activity}/import-result/clear-invalid-activities', [
            'as'   => 'activity.result.clear-invalid-activities',
            'uses' => 'ImportResultController@clearInvalidActivities'
        ]);

        $router->get('/result/check-session-status', [
            'as'   => 'activity.result.check-session-status',
            'uses' => 'ImportResultController@checkSessionStatus'
        ]);

        $router->post('/activity/{activity}/import-result/cancel-import', [
            'as'   => 'activity.result.cancel-import',
            'uses' => 'ImportResultController@cancel'
        ]);

        $router->get('/activity/{activity}/import-result/get-data', [
            'as'   => 'activity.result.get-data',
            'uses' => 'ImportResultController@getData'
        ]);

        $router->get('/activity/{activity}/import-result/upload-csv-redirect', [
            'as'   => 'activity.result.upload-redirect',
            'uses' => 'ImportResultController@uploadRedirect'
        ]);
    }
);
