<?php

$router->get('/', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->resource('settings', 'Complete\SettingsController');

$router->get('test',function(){
    dd(trans('201/codelist'));
});
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