<?php

$router->group(
    ['domain' => 'tz.' . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'Settings'],
            function ($router) {
                $router->resource('settings', 'SettingsController');
            }
        );
    }
);
