<?php

$router->group(['namespace' => 'Lite\Profile', 'middleware' => 'auth.systemVersion'], function ($router) {
    $router->get('lite/user/profile', [
        'as'   => 'lite.user.profile.index',
        'uses' => 'ProfileController@index'
    ]);
    $router->get('lite/user/profile/edit', [
        'as'   => 'lite.user.profile.edit',
        'uses' => 'ProfileController@editProfile'
    ]);
    $router->put('lite/user/profile/store', [
        'as'   => 'lite.user.profile.store',
        'uses' => 'ProfileController@storeProfile'
    ]);
    $router->get('lite/user/password/edit', [
        'as'   => 'lite.user.password.edit',
        'uses' => 'ProfileController@editPassword'
    ]);
    $router->put('lite/user/password/store', [
        'as'   => 'lite.user.password.store',
        'uses' => 'ProfileController@storePassword'
    ]);
});
