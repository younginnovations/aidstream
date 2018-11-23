<?php

$router->group(['namespace' => 'Np\Workflow', 'middleware' => 'auth.systemVersion'], function ($router) {
    $router->post('np/activity/{activity}/complete', [
        'as'   => 'np.activity.complete',
        'uses' => 'WorkflowController@complete'
    ]);
    $router->post('np/activity/{activity}/verify', [
        'as'   => 'np.activity.verify',
        'uses' => 'WorkflowController@verify'
    ]);
    $router->post('np/activity/{activity}/publish', [
        'as'   => 'np.activity.publish',
        'uses' => 'WorkflowController@publish'
    ]);
});
