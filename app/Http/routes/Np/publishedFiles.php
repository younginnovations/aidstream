<?php

$router->group(
    ['namespace' => 'Np\PublishedFiles', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get(
            '/published-files',
            [
                'as'   => 'np.published-files.index',
                'uses' => 'PublishedFilesController@index'
            ]
        );
        $router->post(
            '/published-files/{id}/delete',
            [
                'as'   => 'np.published-files.delete',
                'uses' => 'PublishedFilesController@destroy'
            ]
        );
        $router->post(
            '/bulk-publish',
            [
                'as'   => 'np.published-files.bulk-publish',
                'uses' => 'PublishedFilesController@bulkPublish'
            ]
        );
    }
);
