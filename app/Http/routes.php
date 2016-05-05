<?php

$language = Cookie::get('language');
if (isset($language)) {
    App::setLocale($language);
}

$router->get(
    '/public/files/xml/{file}',
    function ($file) {
        return redirect('/files/xml/' . $file);
    }
);

$router->get('/', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->get('about', 'AboutController@index');
$router->get('who-is-using', 'WhoIsUsingController@index');
$router->get('who-is-using/{page}/{count}', 'WhoIsUsingController@listOrganization');
$router->get('admin/dashboard', 'SuperAdmin\OrganizationController@adminDashboard');
$router->resource('settings', 'Complete\SettingsController');
$router->get('who-is-using/{organization_id}', 'WhoIsUsingController@getDataForOrganization');

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

$router->post(
    'check-organization-user-identifier',
    [
        'as'   => 'check-organization-user-identifier',
        'uses' => 'Auth\AuthController@checkUserIdentifier'
    ]
);

$router->get('logs', 'LogViewerController@index');

$router->get(
    'show-logs',
    [
        'as'   => 'show-logs',
        'uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
    ]
);

$router->get(
    'admin/activity-log',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.activity-log',
        'uses'       => 'Complete\AdminController@index'
    ]
);

$router->get(
    'admin/activity-log/organization/{orgId}',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.activity-log.organization',
        'uses'       => 'Complete\AdminController@index'
    ]
);

$router->get(
    'admin/activity-log/{id}',
    [
        'as'   => 'admin.activity-log.view-data',
        'uses' => 'Complete\AdminController@viewData'
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
$router->get(
    'document/{id}/delete',
    [
        'as'   => 'document.delete',
        'uses' => 'Complete\DocumentController@destroy'
    ]
);
$router->get(
    'validate-activity/{id}',
    [
        'as'   => 'validate-activity',
        'uses' => 'CompleteValidateController@validateActivity'
    ]
);
$router->get(
    'validate-activity/{id}/version/{version?}',
    [
        'as'   => 'validate-activity',
        'uses' => 'CompleteValidateController@validateActivity'
    ]
);
$router->get(
    'validate-organization/{id}',
    [
        'as'   => 'validate-organization',
        'uses' => 'CompleteValidateController@validateOrganization'
    ]
);
$router->get(
    'validate-organization/{id}/version/{version?}',
    [
        'as'   => 'validate-organization',
        'uses' => 'CompleteValidateController@validateOrganization'
    ]
);
$router->get(
    'admin/updateOrganizationIdForUserActivities',
    [
        'as'   => 'admin.updateOrganizationIdForUserActivities',
        'uses' => 'Complete\AdminController@updateOrganizationIdForUserActivities'
    ]
);
