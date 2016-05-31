<?php

$router->group(
    ['domain' => config('tz.domain.subdomain') . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'Settings'],
            function ($router) {
                $router->resource('settings', 'SettingsController');
            }
        );
    }
);
