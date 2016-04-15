<?php

$router->group(
    ['namespace' => 'Auth'],
    function ($router) {
        $router->get('user/profile', [
            'as'   => 'user.profile',
            'uses' => 'UserController@viewProfile'
        ]);
        $router->get(
            'user/change-username/{id}',
            [
                'as'   => 'user.change-username',
                'uses' => 'UserController@changeUsername'
            ]
        );
        $router->post(
            'user/update-username/{id}',
            [
                'as'   => 'user.update-username',
                'uses' => 'UserController@updateUsername'
            ]
        );
        $router->get(
            'user/edit-profile/{id}',
            [
                'as'   => 'user.edit-profile',
                'uses' => 'UserController@editProfile'
            ]
        );
        $router->post(
            'user/update-profile/{id}',
            [
                'as'   => 'user.update-profile',
                'uses' => 'UserController@updateProfile'
            ]
        );
        $router->get(
            'user/reset-user-password/{id}',
            [
                'as'   => 'user.reset-user-password',
                'uses' => 'UserController@resetUserPassword'
            ]
        );

        $router->post
        (
            'user/update-user-password/{id}',
            [
                'as'   => 'user.update-user-password',
                'uses' => 'UserController@updateUserPassword'
            ]
        );
    }
);
