<?php

$language = Cookie::get('language');
if (isset($language)) {
    App::setLocale($language);
}

$router->get('/', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->resource('settings', 'Complete\SettingsController');

$router->get(
    'test',
    function () {
        dd(trans('201/codelist'));
    }
);
$router->controllers(
    [
        'auth'     => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]
);

if (getenv('APP_ENV') == "local") {
    $router->get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}

$router->get(
    'admin/activity-log',
    [
        'as'   => 'admin.activity-log',
        'uses' => 'Complete\AdminController@index'
    ]
);

$router->get(
    'admin/register-user',
    [
        'as'   => 'admin.register-user',
        'uses' => 'Complete\AdminController@create'
    ]
);

$router->get(
    'admin/list-users',
    [
        'as'   => 'admin.list-users',
        'uses' => 'Complete\AdminController@listUsers'
    ]
);

$router->post(
    'admin/list-users',
    [
        'as'   => 'admin.signup-user',
        'uses' => 'Complete\AdminController@store'
    ]
);

$router->get(
    'admin/view-profile/{id}',
    [
        'as'   => 'admin.view-profile',
        'uses' => 'Complete\AdminController@viewUserProfile'
    ]
);

$router->delete(
    'admin/{id}',
    [
        'as'   => 'admin.delete-user',
        'uses' => 'Complete\AdminController@deleteUser'
    ]
);

$router->get(
    'admin/reset-user-password/{id}',
    [
        'as'   => 'admin.reset-user-password',
        'uses' => 'Complete\AdminController@resetUserPassword'
    ]
);

$router->post
(
    'admin/update-user-password/{id}',
    [
        'as'   => 'admin.update-user-password',
        'uses' => 'Complete\AdminController@updateUserPassword'
    ]
);


$router->get
(
    'admin/edit-user-permission/{id}',
    [
        'as'   => 'admin.edit-user-permission',
        'uses' => 'Complete\AdminController@editUserPermission'
    ]
);

$router->post
(
    'admin/update-user-permission/{id}',
    [
        'as'   => 'admin.update-user-permission',
        'uses' => 'Complete\AdminController@updateUserPermission'
    ]
);

