<?php

$router->group(['namespace' => 'Lite\Settings', 'middleware' => 'auth.systemVersion'], function ($router) {
//    $router->get('lite/settings', [
//        'as'   => 'lite.settings.index',
//        'uses' => 'SettingsController@index'
//    ]);
    $router->get('lite/settings', [
        'as'   => 'lite.settings.edit',
        'uses' => 'SettingsController@edit'
    ]);
    $router->put('lite/settings/store', [
        'as'   => 'lite.settings.store',
        'uses' => 'SettingsController@store'
    ]);
    $router->get('lite/settings/confirm-upgrade', [
        'as'   => 'lite.settings.confirm-upgrade',
        'uses' => 'SettingsController@confirmUpgrade'
    ]);
    $router->post('lite/settings/upgrade-version', [
        'as'   => 'lite.settings.upgrade-version',
        'uses' => 'SettingsController@upgradeVersion'
    ]);
});
