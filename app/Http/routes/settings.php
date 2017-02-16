<?php

$router->get(
    'settings',
    [
        'as'   => 'settings',
        'uses' => 'Complete\Organization\OrganizationController@viewOrganizationInformation'
    ]

);

$router->get(
    'settings/organization-user',
    [
        'as'   => 'settings.organization-user',
        'uses' => 'Complete\SettingsController@index'
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

$router->post
(
    'organization-user/update-permission/{id}',
    [
        'as'   => 'admin.update-user-permission',
        'uses' => 'Complete\AdminController@updateUserPermission'
    ]
);

$router->get(
    'publishing-settings',
    [
        'as'   => 'publishing-settings',
        'uses' => 'Complete\SettingsController@viewPublishingInfo'
    ]
);

$router->post(
    'publishing-settings/update',
    [
        'as'   => 'publishing-settings.update',
        'uses' => 'Complete\SettingsController@savePublishingInfo'
    ]
);

$router->get(
    'default-values',
    [
        'as'   => 'default-values',
        'uses' => 'Complete\SettingsController@viewDefaultValues'
    ]
);

$router->post(
    'default-values/update',
    [
        'as'   => 'default-values.update',
        'uses' => 'Complete\SettingsController@saveDefaultValues'
    ]
);

$router->get(
    'activity-elements-checklist',
    [
        'as'   => 'activity-elements-checklist',
        'uses' => 'Complete\SettingsController@viewActivityElementsChecklist'
    ]
);

$router->post(
    'activity-elements-checklist/update',
    [
        'as'   => 'activity-elements-checklist.update',
        'uses' => 'Complete\SettingsController@saveActivityElementsChecklist'
    ]
);

$router->post(
    'organization-information/update',
    [
        'as'   => 'organization-information.update',
        'uses' => 'Complete\Organization\OrganizationController@saveOrganizationInformation'
    ]
);

$router->get(
    'organization-information/username-updated',
    [
        'as'   => 'organization-information.username-updated',
        'uses' => 'Complete\Organization\OrganizationController@updateUsername'
    ]
);

$router->post(
    'organization-information/notify-user',
    [
        'as'   => 'organization-information.notify-user',
        'uses' => 'Complete\Organization\OrganizationController@notifyUser'
    ]
);

$router->post(
    'publishing-settings/verifyPublisherAndApi',
    [
        'as'   => 'publishing-settings.verifyPublisherAndApi',
        'uses' => 'Complete\SettingsController@verifyPublisherAndApi'
    ]
);