<?php

$router->group(
    ['namespace' => 'Complete\Activity\Import\Transaction'],
    function ($router) {

        $router->get(
            '/activity/{activity}/import-transaction/upload-csv',
            [
                'as'   => 'activity.transaction.upload-csv',
                'uses' => 'ImportTransactionController@index'
            ]
        );

        $router->post(
            '/activity/{activity}/import-transaction/import-csv',
            [
                'as'   => 'activity.transaction.import-csv',
                'uses' => 'ImportTransactionController@store'
            ]
        );

        $router->get(
            '/import-transaction/download-simple-csv',
            [
                'as'   => 'activity.import-transaction.download-simple-csv',
                'uses' => 'ImportTransactionController@downloadSimpleTransactionTemplate'
            ]
        );

        $router->get(
            '/import-transaction/download-detailed-csv',
            [
                'as'   => 'activity.import-transaction.download-detailed-csv',
                'uses' => 'ImportTransactionController@downloadDetailedTransactionTemplate'
            ]
        );

        $router->get(
            '/activity/{activity}/import-transaction/status',
            [
                'as'   => 'activity.import-transaction.status',
                'uses' => 'ImportTransactionController@status'
            ]
        );

        $router->get(
            '/activity/{activity}/import-transaction/check-status',
            [
                'as'   => 'activity.import-transaction.check-status',
                'uses' => 'ImportTransactionController@checkStatus'
            ]
        );

        $router->get(
            '/activity/{activity}/import-transaction/get-data',
            [
                'as'   => 'import-transaction.get-data',
                'uses' => 'ImportTransactionController@getData'
            ]
        );

        $router->post(
            'activity/{activity}/import-transaction/cancel-import',
            [
                'as'   => 'activity.import-transaction.cancel',
                'uses' => 'ImportTransactionController@cancel'
            ]
        );

        $router->post(
            'activity/{activity}/import-transaction/validated-transactions',
            [
                'as'   => 'activity.import-transaction.validated-transactions',
                'uses' => 'ImportTransactionController@validatedTransactions'
            ]
        );

        $router->get(
            'activity/{activity}/import-transaction/upload-csv-redirect',
            [
                'as'   => 'activity.import-transaction.upload-csv-redirect',
                'uses' => 'ImportTransactionController@uploadRedirect'
            ]
        );

        $router->get(
            'activity/{activity}/import-transaction/get-remaining-valid-data',
            [
                'as'   => 'activity.import-transaction.get-remaining-valid-data',
                'uses' => 'ImportTransactionController@getRemainingValidData'
            ]
        );

        $router->get(
            'activity/{activity}/import-transaction/get-remaining-invalid-data',
            [
                'as'   => 'activity.import-transaction.get-remaining-valid-data',
                'uses' => 'ImportTransactionController@getRemainingInvalidData'
            ]
        );
    }
);

