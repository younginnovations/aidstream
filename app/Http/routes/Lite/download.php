<?php

$router->group(
    ['namespace' => 'Lite\CsvDownload'],
    function ($router) {
        $router->get(
            '/lite/csv/download',
            [
                'as'   => 'lite.csv.download',
                'uses' => 'CsvDownloadController@downloadSimpleCsv'
            ]
        );
    }
);