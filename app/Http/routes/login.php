<?php

$router->get('/auth/login', [
    'as'   => 'login.overridden',
    'uses' => 'Auth\LoginController@getLogin'
]);

$router->post('/auth/login', [
    'as'   => 'login.post-overridden',
    'uses' => 'Auth\LoginController@postLogin'
]);
