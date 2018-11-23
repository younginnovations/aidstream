<?php

use Illuminate\Support\Facades\Route;

$subdomainRoutes = function () {
    Route::group(
        ['namespace' => 'Lite'],
        function () {
            Route::group(
                ['namespace' => 'Activity'],
                function () {
                    Route::get(
                        '/lite/activity',
                        [
                            'as'   => 'lite.activity.index',
                            'uses' => 'ActivityController@index'
                        ]
                    );
                    Route::get(
                        '/lite/activity/create',
                        [
                            'as'   => 'lite.activity.create',
                            'uses' => 'ActivityController@create'
                        ]
                    );
                    Route::post(
                        '/lite/activity/store',
                        [
                            'as'   => 'lite.activity.store',
                            'uses' => 'ActivityController@store'
                        ]
                    );
                    Route::get(
                        '/lite/activity/{activity}',
                        [
                            'as'   => 'lite.activity.show',
                            'uses' => 'ActivityController@show'
                        ]
                    );
                    Route::get(
                        '/lite/activity/{activity}/edit',
                        [
                            'as'   => 'lite.activity.edit',
                            'uses' => 'ActivityController@edit'
                        ]
                    );
                    Route::get(
                        '/lite/activity/duplicate/{activity}/edit',
                        [
                            'as'   => 'lite.activity.duplicate.edit',
                            'uses' => 'ActivityController@createDuplicate'
                        ]
                    );
                    Route::post(
                        '/lite/activity/duplicate',
                        [
                            'as'   => 'lite.activity.duplicate',
                            'uses' => 'ActivityController@duplicate'
                        ]
                    );
                    Route::post(
                        '/lite/activity/delete',
                        [
                            'as'   => 'lite.activity.delete',
                            'uses' => 'ActivityController@destroy'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/update',
                        [
                            'as'   => 'lite.activity.update',
                            'uses' => 'ActivityController@update'
                        ]
                    );

                    Route::get(
                        '/lite/budgetDetails',
                        [
                            'as'   => 'lite.activity.budgetDetails',
                            'uses' => 'ActivityController@budgetDetails'
                        ]
                    );

                    Route::get(
                        '/lite/activity/{activity}/budget/create',
                        [
                            'as'   => 'lite.activity.budget.create',
                            'uses' => 'ActivityController@createBudget'
                        ]
                    );

                    Route::get(
                        '/lite/activity/{activity}/budget/edit',
                        [
                            'as'   => 'lite.activity.budget.edit',
                            'uses' => 'ActivityController@editBudget'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/budget/store',
                        [
                            'as'   => 'lite.activity.budget.store',
                            'uses' => 'ActivityController@storeBudget'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/budget/update',
                        [
                            'as'   => 'lite.activity.budget.update',
                            'uses' => 'ActivityController@updateBudget'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/budget/delete',
                        [
                            'as'   => 'lite.activity.budget.delete',
                            'uses' => 'ActivityController@deleteBudget'
                        ]
                    );

                    Route::get(
                        '/lite/activity/{activity}/transaction/{type}/create',
                        [
                            'as'   => 'lite.activity.transaction.create',
                            'uses' => 'ActivityController@createTransaction'
                        ]
                    );

                    Route::get(
                        '/lite/activity/{activity}/transaction/{type}/edit',
                        [
                            'as'   => 'lite.activity.transaction.edit',
                            'uses' => 'ActivityController@editTransaction'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/transaction/{type}/store',
                        [
                            'as'   => 'lite.activity.transaction.store',
                            'uses' => 'ActivityController@storeTransaction'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/transaction/{type}/update',
                        [
                            'as'   => 'lite.activity.transaction.update',
                            'uses' => 'ActivityController@updateTransaction'
                        ]
                    );

                    Route::post(
                        '/lite/activity/{activity}/transaction/delete',
                        [
                            'as'   => 'lite.activity.transaction.delete',
                            'uses' => 'ActivityController@deleteTransaction'
                        ]
                    );
                    Route::get(
                        '/lite/reverseGeoCode',
                        [
                            'as'   => 'lite.reverseGeoCode',
                            'uses' => 'ActivityController@reverseGeoCode'
                        ]
                    );
                }
            );
        }
    );
};

Route::group(
    ['namespace' => 'Tz', 'domain' => env('TZ_DOMAIN')],
    function () {
        Route::get(
            '/',
            [
                'as'   => 'tz.home',
                'uses' => 'TzController@index'
            ]
        );
        Route::get(
            '/about',
            [
                'as'   => 'tz.about',
                'uses' => 'TzController@about'
            ]
        );

        Route::get('/api/activities', 'TzController@activities');
    }
);


if (isTzSubDomain()) {
    Route::group(['domain' => env('TZ_DOMAIN'), 'middleware' => 'auth.systemVersion'], $subdomainRoutes);
} else {
    Route::group(['domain' => env('CORE_DOMAIN'), 'middleware' => 'auth.systemVersion'], $subdomainRoutes);
    Route::group(
        ['domain' => env('CORE_DOMAIN'), 'middleware' => 'auth.systemVersion'],
        function () {
            Route::get(
                '/lite/activity',
                [
                    'as'   => 'lite.activity.index',
                    'uses' => 'Lite\Activity\ActivityController@index'
                ]
            );
        }
    );
}
