<?php

$router->group(
    ['namespace' => 'Complete', 'middleware' => 'auth.systemVersion'],
    function ($router) {
        $router->post(
            'publish/activity',
            [
                'as'   => 'activity.bulk-publish',
                'uses' => 'BulkPublishController@activityBulkPublishToRegistry'
            ]
        );

        $router->post(
            'publish/org-files',
            [
                'as'   => 'org.bulk-publish',
                'uses' => 'BulkPublishController@orgBulkPublishToRegistry'
            ]
        );
    }
);
