<?php

$router->group(['namespace' => 'Np\Settings', 'middleware' => 'auth.systemVersion'], function ($router) {
    $router->get('setting', [
        'as'   => 'np.settings.edit',
        'uses' => 'SettingsController@edit'
    ]);
    $router->put('setting/store', [
        'as'   => 'np.settings.store',
        'uses' => 'SettingsController@store'
    ]);
    $router->get('setting/confirm-upgrade', [
        'as'   => 'np.settings.confirm-upgrade',
        'uses' => 'SettingsController@confirmUpgrade'
    ]);
    $router->post('setting/upgrade-version', [
        'as'   => 'np.settings.upgrade-version',
        'uses' => 'SettingsController@upgradeVersion'
    ]);
});
