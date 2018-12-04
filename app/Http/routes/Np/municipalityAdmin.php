<?php

$router->group(
    ['namespace' => 'MunicipalityAdmin', 'MunicipalityAdmin' => true],
    function ($router) {
        $router->get(
            'municipality/admin/dashboard',
            [
                'as'   => 'municipalityAdmin.dashboard',
                'uses' => 'OrganizationController@dashboard'
            ]
        );
        $router->get(
            'municipality/admin/list-organization',
            [
                'as'   => 'municipalityAdmin.list-organization',
                'uses' => 'OrganizationController@oldListOrganizations'
            ]
        );
        $router->get(
            'municipality/admin/list-activities',
            [
                'as'   => 'municipalityAdmin.list-activities',
                'uses' => 'OrganizationController@listAllActivities'
            ]
        );
        $router->get(
            'municipality/admin/activity/{activity_id}',
            [
                'as'   => 'municipalityAdmin.activityShow',
                'uses' => 'OrganizationController@showActivity'
            ]
        );
        $router->get(
            'municipality/admin/masquerade-organization/{orgId?}/user/{userId?}',
            [
                'as'   => 'municipalityAdmin.masquerade-organization',
                'uses' => 'OrganizationController@masqueradeOrganization'
            ]
        );
        $router->get(
            'municipality/admin/switch-back',
            [
                'as'   => 'municipalityAdmin.switch-back',
                'uses' => 'OrganizationController@switchBackAsMunicipalityAdmin'
            ]
        );
        $router->get(
            'admin/search-organization',
            [
                'as'   => 'admin.search-organization',
                'uses' => 'OrganizationController@searchOrganizations'
            ]
        );
    }
);
