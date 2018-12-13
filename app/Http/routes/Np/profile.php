<?php

$router->group(
    [
        'namespace' => 'Np\Profile',
        'domain'    => env('NP_DOMAIN'),
        'middleware' => 'auth.systemVersion'
    ],
    function ($router) {
        $router->get('/user/profile', [
            'as'   => 'np.user.profile.index',
            'uses' => 'ProfileController@index'
        ]);
        $router->get('/user/profile/edit', [
            'as'   => 'np.user.profile.edit',
            'uses' => 'ProfileController@editProfile'
        ]);
        $router->put('/user/profile/store', [
            'as'   => 'np.user.profile.store',
            'uses' => 'ProfileController@storeProfile'
        ]);
        $router->get('/user/password/edit', [
            'as'   => 'np.user.password.edit',
            'uses' => 'ProfileController@editPassword'
        ]);
        $router->put('/user/password/store', [
            'as'   => 'np.user.password.store',
            'uses' => 'ProfileController@storePassword'
        ]);
    }
);
