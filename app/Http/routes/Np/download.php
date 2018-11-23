<?php

$router->group(
    ['namespace' => 'Lite\CsvDownload'],
    function ($router) {
        $router->get(
            '/csv/download',
            [
                'as'   => 'np.csv.download',
                'uses' => 'CsvDownloadController@downloadSimpleCsv'
            ]
        );
    }
);