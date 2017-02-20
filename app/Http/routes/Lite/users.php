<?php

$router->group(
    ['namespace' => 'Lite', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->group(
            ['namespace' => 'Users'],
            function ($router) {
                $router->get(
                    '/lite/users',
                    [
                        'as'   => 'lite.users.index',
                        'uses' => 'UserController@index'
                    ]
                );

                $router->get(
                    '/lite/users/create',
                    [
                        'as'   => 'lite.users.create',
                        'uses' => 'UserController@create'
                    ]
                );

                $router->post(
                    '/lite/users/store',
                    [
                        'as'   => 'lite.users.store',
                        'uses' => 'UserController@store'
                    ]
                );

                $router->get(
                    '/lite/users/delete/{id}',
                    [
                        'as'   => 'lite.users.delete',
                        'uses' => 'UserController@destroy'
                    ]
                );

                $router->post(
                    '/lite/users/update-permission/{id}',
                    [
                        'as'   => 'lite.users.update-permission',
                        'uses' => 'UserController@UpdatePermission'
                    ]
                );

                $router->post(
                    '/lite/users/notify-user',
                    [
                        'as'   => 'lite.users.notify-user',
                        'uses' => 'UserController@notifyUsernameChanged'
                    ]
                );
            }
        );
    }
);