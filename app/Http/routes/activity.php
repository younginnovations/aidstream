<?php

$router->group(
    ['namespace' => 'Complete\Activity'],
    function ($router) {
        $router->resource('activity', 'ActivityController');
        $router->resource('activity.reporting-organization', 'ReportingOrganization');
        $router->resource('activity.iati-identifier', 'IatiIdentifierController');
        $router->resource('activity.other-identifier', 'OtherIdentifierController');
        $router->resource('activity.title', 'TitleController');
        $router->resource('activity.description', 'DescriptionController');
        $router->resource('activity.activity-status', 'ActivityStatusController');
    }
);
