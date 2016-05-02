<?php

$router->group(
    ['namespace' => 'SuperAdmin'],
    function () use ($router) {
        $router->get(
            'correct-published-files/{organizationId}',
            [
                'as'   => 'superadmin.correct-published-files',
                'uses' => 'PublishedFilesCorrectionController@show'
            ]
        );

        $router->delete(
            'delete-xml-file/{organizationId}/{fileId}',
            [
                'as'   => 'superadmin.deleteXmlFile',
                'uses' => 'PublishedFilesCorrectionController@deleteXmlFile'
            ]
        );

        $router->get(
            'unlink-xml-file/{organizationId}/{fileId}',
            [
                'as'   => 'superadmin.unlinkXmlFile',
                'uses' => 'PublishedFilesCorrectionController@unlinkXmlFile'
            ]
        );

        $router->get(
            'unlink-org-xml-file/{organizationId}/{fileId}',
            [
                'as'   => 'superadmin.unlinkOrganizationXmlFile',
                'uses' => 'PublishedFilesCorrectionController@unlinkOrganizationXmlFile'
            ]
        );

        $router->delete(
            'delete-org-xml-file/{organizationId}/{fileId}',
            [
                'as'   => 'superadmin.deleteOrganizationXmlFile',
                'uses' => 'PublishedFilesCorrectionController@deleteOrganizationXmlFile'
            ]
        );

        $router->get(
            'resync-org-registry-data/{organizationId}',
            [
                'as'   => 'superadmin.reSyncOrganizationData',
                'uses' => 'PublishedFilesCorrectionController@reSyncOrganizationData'
            ]
        );

        $router->get(
            'resync-registry-data/{organizationId}',
            [
                'as'   => 'superadmin.reSync',
                'uses' => 'PublishedFilesCorrectionController@reSyncRegistryData'
            ]
        );

    }
);
