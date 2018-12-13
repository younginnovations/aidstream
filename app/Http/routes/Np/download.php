<?php

$router->group(
    ['namespace' => 'Np'],
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