<?php

$router->group(['namespace' => 'Np\Profile', 'middleware' => 'auth.systemVersion'], function ($router) {
    $router->get('/usr/profile', [
        'as'   => 'np.user.profile.index',
        'uses' => 'ProfileController@index'
    ]);
    $router->get('/usr/profile/edit', [
        'as'   => 'np.user.profile.edit',
        'uses' => 'ProfileController@editProfile'
    ]);
    $router->put('/usr/profile/store', [
        'as'   => 'np.user.profile.store',
        'uses' => 'ProfileController@storeProfile'
    ]);
    $router->get('/usr/password/edit', [
        'as'   => 'np.user.password.edit',
        'uses' => 'ProfileController@editPassword'
    ]);
    $router->put('/usr/password/store', [
        'as'   => 'np.user.password.store',
        'uses' => 'ProfileController@storePassword'
    ]);
});
