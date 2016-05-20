<?php

$router->group(['domain' => 'tz.' . env('HOST'), 'namespace' => 'Tz'], function ($router) {
    $router->resource('project', 'ProjectController');
    // ...
});
