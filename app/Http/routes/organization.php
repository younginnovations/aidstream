<?php

$router->group(
    ['namespace' => 'Complete\Organization', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->get(
            'organization/{id}/create',
            [
                'as'   => 'organization.create',
                'uses' => 'OrganizationController@create'
            ]
        );
        $router->post('organization/{id}/store', 'OrganizationController@store');
        $router->get('organization/{id}/identifier', 'OrganizationController@showIdentifier');
        $router->resource('organization', 'OrganizationController', ['except' => 'create']);
        $router->post('organization/{id}/update-status', 'OrganizationController@updateStatus');
        $router->resource('organization.reportingOrg', 'OrgReportingOrgController');
        $router->resource('organization.name', 'NameController');
        $router->resource('organization.total-budget', 'OrgTotalBudgetController');
        $router->resource('organization.recipient-organization-budget', 'RecipientOrganizationBudgetController');
        $router->resource('organization.recipient-region-budget', 'RecipientRegionBudgetController');
        $router->resource('organization.recipient-country-budget', 'RecipientCountryBudgetController');
        $router->resource('organization.total-expenditure', 'TotalExpenditureController');
        $router->resource('organization.document-link', 'DocumentLinkController');

        $router->get(
            'list-published-files/{action?}/{id?}',
            [
                'as'   => 'list-published-files',
                'uses' => 'OrganizationController@listPublishedFiles'
            ]
        );

        $router->get(
            'organization/{id}/delete-element/{element}',
            [
                'as'   => 'organization.delete-element',
                'uses' => 'OrganizationController@deleteElement'
            ]
        );

        $router->get(
            'organization/{orgId}/view/xml',
            [
                'as'   => 'view.organizationXml',
                'uses' => 'OrganizationController@viewOrganizationXml'
            ]
        );

        $router->get(
            'organization/{orgId}/download/xml',
            [
                'as'   => 'download.organizationXml',
                'uses' => 'OrganizationController@downloadOrganizationXml'
            ]
        );

        $router->get(
            '/organization-data/{id}/delete',
            [
                'as'   => 'organization-data.delete',
                'uses' => 'OrganizationController@deleteOrganizationData'
            ]
        );

        $router->get(
            '/organization-data/{id}/edit',
            [
                'as'   => 'organization-data.edit',
                'uses' => 'OrganizationController@edit'
            ]
        );

        $router->post(
            '/organization-data/{id}/update',
            [
                'as'   => 'organization-data.update',
                'uses' => 'OrganizationController@update'
            ]
        );

        $router->post(
            '/organizationData/{organizationDataId}/updateStatus',
            [
                'as'   => 'organizationData.updateStatus',
                'uses' => 'OrganizationController@updateOrganizationDataStatus'
            ]
        );

        $router->get(
            '/organization/{organizationId}/xml/view/{true}',
            [
                'as'   => 'errors.organizationXml',
                'uses' => 'OrganizationController@viewOrganizationXml'
            ]
        );

        $router->post('/organization/{organizationDataId}/unpublish', [
            'as'   => 'organization-data.unpublish',
            'uses' => 'OrganizationController@unpublishOrganizationData'
        ]);
    }
);
