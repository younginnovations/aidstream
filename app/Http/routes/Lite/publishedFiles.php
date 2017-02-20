<?php

$router->group(
    ['namespace' => 'Lite\PublishedFiles', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get(
            '/lite/published-files',
            [
                'as'   => 'lite.published-files.index',
                'uses' => 'PublishedFilesController@index'
            ]
        );
        $router->post(
            '/lite/published-files/{id}/delete',
            [
                'as'   => 'lite.published-files.delete',
                'uses' => 'PublishedFilesController@destroy'
            ]
        );
        $router->post(
            '/lite/bulk-publish',
            [
                'as'   => 'lite.published-files.bulk-publish',
                'uses' => 'PublishedFilesController@bulkPublish'
            ]
        );
    }
);
