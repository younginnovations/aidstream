<?php

$router->group(
    ['namespace' => 'MunicipalityAdmin', 'MunicipalityAdmin' => true],
    function ($router) {
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
            'admin/list-organization/lite-demo',
            [
                'as'   => 'admin.list-organization.tz',
                'uses' => 'OrganizationController@listOrganizations'
            ]
        );
        $router->get(
            'admin/add-organization',
            [
                'as'   => 'admin.add-organization',
                'uses' => 'OrganizationController@add'
            ]
        );
        $router->post(
            'admin/add-organization',
            [
                'as'   => 'admin.add-organization',
                'uses' => 'OrganizationController@save'
            ]
        );
        $router->get(
            'admin/edit-organization/{id}',
            [
                'as'   => 'admin.edit-organization',
                'uses' => 'OrganizationController@add'
            ]
        );
        $router->put(
            'admin/edit-organization/{id}',
            [
                'as'   => 'admin.edit-organization',
                'uses' => 'OrganizationController@save'
            ]
        );
        $router->get(
            'admin/change-organization-status/org_id/{id}/status/{status}',
            [
                'as'   => 'admin.change-organization-status',
                'uses' => 'OrganizationController@changeOrganizationStatus'
            ]
        );
        $router->get(
            'admin/delete-organization/{id}',
            [
                'as'   => 'admin.delete-organization',
                'uses' => 'OrganizationController@deleteOrganization'
            ]
        );
        $router->get(
            'admin/masquerade-organization/{orgId?}/user/{userId?}',
            [
                'as'   => 'admin.masquerade-organization',
                'uses' => 'OrganizationController@masqueradeOrganization'
            ]
        );
        $router->get(
            'admin/switch-back',
            [
                'as'   => 'admin.switch-back',
                'uses' => 'OrganizationController@switchBackAsSuperAdmin'
            ]
        );
        $router->get(
            'admin/group-organizations',
            [
                'as'   => 'admin.group-organizations',
                'uses' => 'OrganizationGroupController@lists'
            ]
        );
        $router->get(
            'admin/create-organization-group',
            [
                'as'   => 'admin.create-organization-group',
                'uses' => 'OrganizationGroupController@create'
            ]
        );
        $router->post(
            'admin/create-organization-group',
            [
                'as'   => 'admin.create-organization-group',
                'uses' => 'OrganizationGroupController@save'
            ]
        );
        $router->get(
            'admin/edit-group/{id}',
            [
                'as'   => 'admin.edit-group',
                'uses' => 'OrganizationGroupController@create'
            ]
        );
        $router->put(
            'admin/edit-group/{id}',
            [
                'as'   => 'admin.edit-group',
                'uses' => 'OrganizationGroupController@save'
            ]
        );
        $router->get(
            'admin/hide-organization/org_id/{id}/status/{status}',
            [
                'as'   => 'admin.hide-organization',
                'uses' => 'OrganizationController@hideOrganization'
            ]
        );
        $router->get(
            'admin/exportOrganizationInfo',
            [
                'as'   => 'admin.exportOrganizationInfo',
                'uses' => 'OrganizationController@exportOrganizationInfo'
            ]
        );
        $router->post(
            'admin/change/system_version/{orgId}',
            [
                'as'   => 'admin.change.system_version',
                'uses' => 'OrganizationController@changeSystemVersion'
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
