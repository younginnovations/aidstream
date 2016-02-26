<?php

$language = Cookie::get('language');
if (isset($language)) {
    App::setLocale($language);
}

$router->get('/', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->get('about', 'AboutController@index');
$router->get('who-are-using', 'WhoAreUsingController@index');
$router->get('admin/dashboard', 'HomeController@adminDashboard');
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
    'organization-user/register',
    [
        'as'   => 'admin.register-user',
        'uses' => 'Complete\AdminController@create'
    ]
);

$router->get(
    'organization-user',
    [
        'as'   => 'admin.list-users',
        'uses' => 'Complete\AdminController@listUsers'
    ]
);

$router->post(
    'organization-user',
    [
        'as'   => 'admin.signup-user',
        'uses' => 'Complete\AdminController@store'
    ]
);

$router->get(
    'organization-user/view-profile/{id}',
    [
        'as'   => 'admin.view-profile',
        'uses' => 'Complete\AdminController@viewUserProfile'
    ]
);

$router->get(
    'organization-user/{id}/delete',
    [
        'as'   => 'admin.delete-user',
        'uses' => 'Complete\AdminController@deleteUser'
    ]
);

$router->get(
    'organization-user/reset-password/{id}',
    [
        'as'   => 'admin.reset-user-password',
        'uses' => 'Complete\AdminController@resetUserPassword'
    ]
);

$router->post
(
    'organization-user/update-password/{id}',
    [
        'as'   => 'admin.update-user-password',
        'uses' => 'Complete\AdminController@updateUserPassword'
    ]
);


$router->get
(
    'organization-user/edit-permission/{id}',
    [
        'as'   => 'admin.edit-user-permission',
        'uses' => 'Complete\AdminController@editUserPermission'
    ]
);

$router->post
(
    'organization-user/update-permission/{id}',
    [
        'as'   => 'admin.update-user-permission',
        'uses' => 'Complete\AdminController@updateUserPermission'
    ]
);

$router->resource('upgrade-version', 'Complete\UpgradeController');
$router->get(
    'documents',
    [
        'as'   => 'documents',
        'uses' => 'Complete\DocumentController@index'
    ]
);
$router->post(
    'document/upload',
    [
        'as'   => 'document.upload',
        'uses' => 'Complete\DocumentController@store'
    ]
);
$router->get(
    'document/list',
    [
        'as'   => 'document.list',
        'uses' => 'Complete\DocumentController@getDocuments'
    ]
);
