<?php

$router->group(
    ['namespace' => 'Np', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->group(
            ['namespace' => 'Users'],
            function ($router) {
                $router->get(
                    '/users',
                    [
                        'as'   => 'np.users.index',
                        'uses' => 'UserController@index'
                    ]
                );

                $router->get(
                    '/users/create',
                    [
                        'as'   => 'np.users.create',
                        'uses' => 'UserController@create'
                    ]
                );

                $router->post(
                    '/users/store',
                    [
                        'as'   => 'np.users.store',
                        'uses' => 'UserController@store'
                    ]
                );

                $router->get(
                    '/users/delete/{id}',
                    [
                        'as'   => 'np.users.delete',
                        'uses' => 'UserController@destroy'
                    ]
                );

                $router->post(
                    '/users/update-permission/{id}',
                    [
                        'as'   => 'np.users.update-permission',
                        'uses' => 'UserController@UpdatePermission'
                    ]
                );

                $router->post(
                    '/users/notify-user',
                    [
                        'as'   => 'np.users.notify-user',
                        'uses' => 'UserController@notifyUsernameChanged'
                    ]
                );
            }
        );
    }
);