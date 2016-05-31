<?php

$router->group(
    ['domain' => 'tz1.' . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'Settings'],
            function ($router) {
                $router->resource('settings', 'SettingsController');
            }
        );
    }
);
