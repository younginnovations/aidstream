<?php

$router->group(
    ['namespace' => 'Complete', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get(
            'download-my-data',
            [
                'as'   => 'download.index',
                'uses' => 'DownloadController@index'
            ]
        );
        $router->get(
            'download-my-data/simple',
            [
                'as'   => 'download.simple',
                'uses' => 'DownloadController@exportSimpleCsv'
            ]
        );
        $router->get(
            'download-my-data/complete',
            [
                'as'   => 'download.complete',
                'uses' => 'DownloadController@exportCompleteCsv'
            ]
        );
        $router->get(
            'download-my-data/transaction',
            [
                'as'   => 'download.transaction',
                'uses' => 'DownloadController@exportTransactionCsv'
            ]
        );
        $router->get(
            '/download-detailed-transaction',
            [
                'as'   => 'download.detailed-transaction',
                'uses' => 'DownloadController@downloadDetailedTransactionTemplate'
            ]
        );
        $router->get(
            '/download-simple-transaction',
            [
                'as'   => 'download.detailed-transaction',
                'uses' => 'DownloadController@downloadSimpleTransactionTemplate'
            ]
        );
        $router->get(
            '/download-activity-template',
            [
                'as'   => 'download.activity-template',
                'uses' => 'DownloadController@downloadActivityTemplate'
            ]
        );
        $router->get(
            '/download-activity-template2',
            [
                'as'   => 'download.activity-template2',
                'uses' => 'DownloadController@downloadActivityTemplate2'
            ]
        );
    }
);
