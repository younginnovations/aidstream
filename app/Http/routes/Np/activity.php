<?php

$router->group(
    [
        'namespace' => 'Np\Activity',
        'domain' => env('NP_DOMAIN'),
        'middleware' => 'auth.systemVersion'
    ],
    function ($router) {
        $router->get(
            '/activity',
            [
                'as' => 'np.activity.index',
                'uses' => 'ActivityController@index',
            ]
        );
        $router->get(
            '/activity/create',
            [
                'as' => 'np.activity.create',
                'uses' => 'ActivityController@create',
            ]
        );
        $router->post(
            '/activity/store',
            [
                'as' => 'np.activity.store',
                'uses' => 'ActivityController@store',
            ]
        );
        $router->get(
            '/activity/{activity}',
            [
                'as' => 'np.activity.show',
                'uses' => 'ActivityController@show',
            ]
        );
        $router->get(
            '/activity/{activity}/edit',
            [
                'as' => 'np.activity.edit',
                'uses' => 'ActivityController@edit',
            ]
        );
        $router->get(
            '/activity/duplicate/{activity}/edit',
            [
                'as' => 'np.activity.duplicate.edit',
                'uses' => 'ActivityController@createDuplicate',
            ]
        );
        $router->post(
            '/activity/duplicate',
            [
                'as' => 'np.activity.duplicate',
                'uses' => 'ActivityController@duplicate',
            ]
        );
        $router->post(
            '/activity/delete',
            [
                'as' => 'np.activity.delete',
                'uses' => 'ActivityController@destroy',
            ]
        );

        $router->post(
            '/activity/{activity}/update',
            [
                'as' => 'np.activity.update',
                'uses' => 'ActivityController@update',
            ]
        );

        $router->get(
            '/budgetDetails',
            [
                'as' => 'np.activity.budgetDetails',
                'uses' => 'ActivityController@budgetDetails',
            ]
        );

        $router->get(
            '/activity/{activity}/budget/create',
            [
                'as' => 'np.activity.budget.create',
                'uses' => 'ActivityController@createBudget',
            ]
        );

        $router->get(
            '/activity/{activity}/budget/edit',
            [
                'as' => 'np.activity.budget.edit',
                'uses' => 'ActivityController@editBudget',
            ]
        );

        $router->post(
            '/activity/{activity}/budget/store',
            [
                'as' => 'np.activity.budget.store',
                'uses' => 'ActivityController@storeBudget',
            ]
        );

        $router->post(
            '/activity/{activity}/budget/update',
            [
                'as' => 'np.activity.budget.update',
                'uses' => 'ActivityController@updateBudget',
            ]
        );

        $router->post(
            '/activity/{activity}/budget/delete',
            [
                'as' => 'np.activity.budget.delete',
                'uses' => 'ActivityController@deleteBudget',
            ]
        );

        $router->get(
            '/activity/{activity}/transaction/{type}/create',
            [
                'as' => 'np.activity.transaction.create',
                'uses' => 'ActivityController@createTransaction',
            ]
        );

        $router->get(
            '/activity/{activity}/transaction/{type}/edit',
            [
                'as' => 'np.activity.transaction.edit',
                'uses' => 'ActivityController@editTransaction',
            ]
        );

        $router->post(
            '/activity/{activity}/transaction/{type}/store',
            [
                'as' => 'np.activity.transaction.store',
                'uses' => 'ActivityController@storeTransaction',
            ]
        );

        $router->post(
            '/activity/{activity}/transaction/{type}/update',
            [
                'as' => 'np.activity.transaction.update',
                'uses' => 'ActivityController@updateTransaction',
            ]
        );

        $router->post(
            '/activity/{activity}/transaction/delete',
            [
                'as' => 'np.activity.transaction.delete',
                'uses' => 'ActivityController@deleteTransaction',
            ]
        );
        $router->get(
            '/reverseGeoCode',
            [
                'as' => 'np.reverseGeoCode',
                'uses' => 'ActivityController@reverseGeoCode',
            ]
        );
    }
 );

