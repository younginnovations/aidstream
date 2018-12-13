<?php

$router->group(
    ['namespace' => 'Np', 'domain' => env('NP_DOMAIN')],
    function ($router) {
        $router->get(
            '/',
            [
                'as'   => 'np.home',
                'uses' => 'NpController@index'
            ]
        );
        $router->get(
            '/about',
            [
                'as'   => 'np.about',
                'uses' => 'NpController@about'
            ]
        );

        $router->get(
            'register',
            [
                'as'   => 'registration',
                'uses' => 'Auth\RegistrationController@showRegistrationForm'
            ]
        );

        $router->post(
            'register',
            [
                'as'   => 'registration.register',
                'uses' => 'Auth\RegistrationController@register'
            ]
        );

        $router->get(
            '/municipality/{id}',
            [
                'as'    => 'municipality.view',
                'uses'  => 'NpController@municipality'
            ]
        );

        $router->get('/api/activities', 'NpController@activities');

        $router->get(
            '/auth/login',
            [
                'as'    => 'login.overridden',
                'uses'  => 'Auth\LoginController@getLogin'
            ]
        );

        $router->post(
            '/auth/login',
            [
                'as'    => 'login.post-overridden',
                'uses'  => 'Auth\LoginController@postLogin'
            ]
        );

        $router->get('who-is-using', 'WhoIsUsingController@index');
        $router->get('who-is-using/{organization_id}', 'WhoIsUsingController@showOrganization');
        $router->get('who-is-using/page/{page}/count/{count}', 'WhoIsUsingController@listOrganization');
        $router->get('who-is-using/{orgId}/{activityId}', 'WhoIsUsingController@showActivity');

        $router->get('forgot/password', 'Auth\PasswordController@showView');
        $router->post('forgot/password', 'Auth\PasswordController@sendResetLinkEmail');

    }
);

