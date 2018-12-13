<?php

$router->group(['namespace' => 'Np\Settings', 'domain' => env('NP_DOMAIN'), 'middleware' => 'auth.systemVersion'], function ($router) {
    $router->get('settings', [
        'as'   => 'np.settings.edit',
        'uses' => 'SettingsController@edit'
    ]);
    $router->put('settings/store', [
        'as'   => 'np.settings.store',
        'uses' => 'SettingsController@store'
    ]);
    $router->get('settings/confirm-upgrade', [
        'as'   => 'np.settings.confirm-upgrade',
        'uses' => 'SettingsController@confirmUpgrade'
    ]);
    $router->post('settings/upgrade-version', [
        'as'   => 'np.settings.upgrade-version',
        'uses' => 'SettingsController@upgradeVersion'
    ]);
});
