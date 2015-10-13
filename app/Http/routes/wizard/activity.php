<?php

$router->group(
    ['namespace' => 'Wizard\Activity', 'prefix' => 'wizard'],
    function ($router) {
        $router->resource('activity', 'ActivityController');
        $router->resource('activity.title-description', 'StepTwoController');
        $router->resource('activity.date-status', 'StepThreeController');
    }
);
