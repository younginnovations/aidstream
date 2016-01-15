<?php

$router->group(
    ['namespace' => 'Complete'],
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
    }
);
