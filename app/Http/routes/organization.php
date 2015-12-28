<?php

$router->group(
    ['namespace' => 'Complete\Organization'],
    function ($router) {
        $router->get('organization/{id}/identifier', 'OrganizationController@showIdentifier');
        $router->resource('organization', 'OrganizationController');
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
    }
);
