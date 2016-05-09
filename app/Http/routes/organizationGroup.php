<?php

$router->group(
    ['namespace' => 'SuperAdmin','SuperAdmin' => true],
    function ($router) {
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
            'admin/delete-group/{id}',
            [
                'as'   => 'admin.delete-group',
                'uses' => 'OrganizationGroupController@delete'
            ]
        );
    }
);
