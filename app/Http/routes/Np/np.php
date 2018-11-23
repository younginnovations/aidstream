<?php

use Illuminate\Support\Facades\Route;

$npSubdomainRoutes = function () {
    Route::group(
        ['namespace' => 'Np','middleware' => 'auth.systemVersion'],
        function () {
            Route::group(
                ['namespace' => 'Activity'],
                function () {
                    Route::get(
                        '/activity',
                        [
                            'as'   => 'np.activity.index',
                            'uses' => 'ActivityController@index'
                        ]
                    );
                    Route::get(
                        '/activity/create',
                        [
                            'as'   => 'np.activity.create',
                            'uses' => 'ActivityController@create'
                        ]
                    );
                    Route::post(
                        '/activity/store',
                        [
                            'as'   => 'np.activity.store',
                            'uses' => 'ActivityController@store'
                        ]
                    );
                    Route::get(
                        '/activity/{activity}',
                        [
                            'as'   => 'np.activity.show',
                            'uses' => 'ActivityController@show'
                        ]
                    );
                    Route::get(
                        '/activity/{activity}/edit',
                        [
                            'as'   => 'np.activity.edit',
                            'uses' => 'ActivityController@edit'
                        ]
                    );
                    Route::get(
                        '/activity/duplicate/{activity}/edit',
                        [
                            'as'   => 'np.activity.duplicate.edit',
                            'uses' => 'ActivityController@createDuplicate'
                        ]
                    );
                    Route::post(
                        '/activity/duplicate',
                        [
                            'as'   => 'np.activity.duplicate',
                            'uses' => 'ActivityController@duplicate'
                        ]
                    );
                    Route::post(
                        '/activity/delete',
                        [
                            'as'   => 'np.activity.delete',
                            'uses' => 'ActivityController@destroy'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/update',
                        [
                            'as'   => 'np.activity.update',
                            'uses' => 'ActivityController@update'
                        ]
                    );

                    Route::get(
                        '/budgetDetails',
                        [
                            'as'   => 'np.activity.budgetDetails',
                            'uses' => 'ActivityController@budgetDetails'
                        ]
                    );

                    Route::get(
                        '/activity/{activity}/budget/create',
                        [
                            'as'   => 'np.activity.budget.create',
                            'uses' => 'ActivityController@createBudget'
                        ]
                    );

                    Route::get(
                        '/activity/{activity}/budget/edit',
                        [
                            'as'   => 'np.activity.budget.edit',
                            'uses' => 'ActivityController@editBudget'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/budget/store',
                        [
                            'as'   => 'np.activity.budget.store',
                            'uses' => 'ActivityController@storeBudget'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/budget/update',
                        [
                            'as'   => 'np.activity.budget.update',
                            'uses' => 'ActivityController@updateBudget'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/budget/delete',
                        [
                            'as'   => 'np.activity.budget.delete',
                            'uses' => 'ActivityController@deleteBudget'
                        ]
                    );

                    Route::get(
                        '/activity/{activity}/transaction/{type}/create',
                        [
                            'as'   => 'np.activity.transaction.create',
                            'uses' => 'ActivityController@createTransaction'
                        ]
                    );

                    Route::get(
                        '/activity/{activity}/transaction/{type}/edit',
                        [
                            'as'   => 'np.activity.transaction.edit',
                            'uses' => 'ActivityController@editTransaction'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/transaction/{type}/store',
                        [
                            'as'   => 'np.activity.transaction.store',
                            'uses' => 'ActivityController@storeTransaction'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/transaction/{type}/update',
                        [
                            'as'   => 'np.activity.transaction.update',
                            'uses' => 'ActivityController@updateTransaction'
                        ]
                    );

                    Route::post(
                        '/activity/{activity}/transaction/delete',
                        [
                            'as'   => 'np.activity.transaction.delete',
                            'uses' => 'ActivityController@deleteTransaction'
                        ]
                    );
                    Route::get(
                        '/reverseGeoCode',
                        [
                            'as'   => 'np.reverseGeoCode',
                            'uses' => 'ActivityController@reverseGeoCode'
                        ]
                    );
                }
            );
        }
    );
};

Route::group(
    ['namespace' => 'Np', 'domain' => env('NP_DOMAIN')],
    function () {
        Route::get(
            '/',
            [
                'as'   => 'np.home',
                'uses' => 'NpController@index'
            ]
        );
        Route::get(
            '/about',
            [
                'as'   => 'np.about',
                'uses' => 'NpController@about'
            ]
        );

        Route::get(
            'register',
            [
                'as'   => 'registration',
                'uses' => 'Auth\RegistrationController@showRegistrationForm'
            ]
        );
        
        Route::post(
            'register',
            [
                'as'   => 'registration.register',
                'uses' => 'Auth\RegistrationController@register'
            ]
        );
        
        Route::get('/api/activities', 'NpController@activities');
    }
);

if (isNpSubDomain()) {
    Route::group(['domain' => env('NP_DOMAIN'), 'middleware' => 'auth.systemVersion'], $npSubdomainRoutes);
}
