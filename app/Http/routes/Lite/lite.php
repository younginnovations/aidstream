<?php

$router->group(
    ['namespace' => 'Lite', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->group(
            ['namespace' => 'Activity'],
            function ($router) {
                $router->get(
                    '/lite/activity',
                    [
                        'as'   => 'lite.activity.index',
                        'uses' => 'ActivityController@index'
                    ]
                );
                $router->get(
                    '/lite/activity/create',
                    [
                        'as'   => 'lite.activity.create',
                        'uses' => 'ActivityController@create'
                    ]
                );
                $router->post(
                    '/lite/activity/store',
                    [
                        'as'   => 'lite.activity.store',
                        'uses' => 'ActivityController@store'
                    ]
                );
                $router->get(
                    '/lite/activity/{activity}',
                    [
                        'as'   => 'lite.activity.show',
                        'uses' => 'ActivityController@show'
                    ]
                );
                $router->get(
                    '/lite/activity/{activity}/edit',
                    [
                        'as'   => 'lite.activity.edit',
                        'uses' => 'ActivityController@edit'
                    ]
                );
                $router->get(
                    '/lite/activity/duplicate/{activity}/edit',
                    [
                        'as'   => 'lite.activity.duplicate.edit',
                        'uses' => 'ActivityController@createDuplicate'
                    ]
                );
                $router->post(
                    '/lite/activity/duplicate',
                    [
                        'as'   => 'lite.activity.duplicate',
                        'uses' => 'ActivityController@duplicate'
                    ]
                );
                $router->post(
                    '/lite/activity/delete',
                    [
                        'as'   => 'lite.activity.delete',
                        'uses' => 'ActivityController@destroy'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/update',
                    [
                        'as'   => 'lite.activity.update',
                        'uses' => 'ActivityController@update'
                    ]
                );

                $router->get(
                    '/lite/budgetDetails',
                    [
                        'as'   => 'lite.activity.budgetDetails',
                        'uses' => 'ActivityController@budgetDetails'
                    ]
                );

                $router->get(
                    '/lite/activity/{activity}/budget/create',
                    [
                        'as'   => 'lite.activity.budget.create',
                        'uses' => 'ActivityController@createBudget'
                    ]
                );

                $router->get(
                    '/lite/activity/{activity}/budget/edit',
                    [
                        'as'   => 'lite.activity.budget.edit',
                        'uses' => 'ActivityController@editBudget'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/budget/store',
                    [
                        'as'   => 'lite.activity.budget.store',
                        'uses' => 'ActivityController@storeBudget'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/budget/update',
                    [
                        'as'   => 'lite.activity.budget.update',
                        'uses' => 'ActivityController@updateBudget'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/budget/delete',
                    [
                        'as'   => 'lite.activity.budget.delete',
                        'uses' => 'ActivityController@deleteBudget'
                    ]
                );

                $router->get(
                    '/lite/activity/{activity}/transaction/{type}/create',
                    [
                        'as'   => 'lite.activity.transaction.create',
                        'uses' => 'ActivityController@createTransaction'
                    ]
                );

                $router->get(
                    '/lite/activity/{activity}/transaction/{type}/edit',
                    [
                        'as'   => 'lite.activity.transaction.edit',
                        'uses' => 'ActivityController@editTransaction'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/transaction/{type}/store',
                    [
                        'as'   => 'lite.activity.transaction.store',
                        'uses' => 'ActivityController@storeTransaction'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/transaction/{type}/update',
                    [
                        'as'   => 'lite.activity.transaction.update',
                        'uses' => 'ActivityController@updateTransaction'
                    ]
                );

                $router->post(
                    '/lite/activity/{activity}/transaction/delete',
                    [
                        'as'   => 'lite.activity.transaction.delete',
                        'uses' => 'ActivityController@deleteTransaction'
                    ]
                );

            }
        );
    }
);
