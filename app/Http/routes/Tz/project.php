<?php

$router->group(
    ['domain' => config('tz.domain.subdomain') . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'Project'],
            function ($router) {
                $router->resource('project', 'ProjectController');
                $router->get(
                    '/project/upload',
                    [
                        'as'   => 'project.upload',
                        'uses' => 'ProjectController@upload'
                    ]
                );

                $router->get(
                    'change-project-defaults/{projectId}',
                    [
                        'as'   => 'change-project-defaults',
                        'uses' => 'ProjectController@changeProjectDefaults'
                    ]
                );

                $router->patch(
                    'override-project-default/{projectId}',
                    [
                        'as'   => 'project.override-project-default',
                        'uses' => 'ProjectController@overrideProjectDefaults'
                    ]
                );

                $router->get(
                    'published-files/list',
                    [
                        'as'   => 'published-files.list',
                        'uses' => 'ProjectController@listPublishedFiles'
                    ]
                );

                $router->get(
                    'users/list',
                    [
                        'as'   => 'users.list',
                        'uses' => 'ProjectController@listUsers'
                    ]
                );

                $router->get(
                    'downloads',
                    [
                        'as'   => 'downloads',
                        'uses' => 'ProjectController@download'
                    ]
                );

                $router->post(
                    'project/{project}/duplicate',
                    [
                        'as'   => 'project.duplicate',
                        'uses' => 'ProjectController@duplicate'
                    ]
                );

                $router->get(
                    'public/view/{orgid}',
                    [
                        'as'   => 'project.public',
                        'uses' => 'ProjectController@projectPublic'
                    ]
                );

                $router->get(
                    '/project/{project}/add-budget',
                    [
                        'as'   => 'project.add-budget',
                        'uses' => 'ProjectController@addBudget'
                    ]
                );
                $router->post(
                    '/project/{project}/budget/store',
                    [
                        'as'   => 'project.budget.store',
                        'uses' => 'ProjectController@storeBudget'
                    ]
                );

                $router->get(
                    '/project/{project}/edit-budget',
                    [
                        'as'   => 'project.edit-budget',
                        'uses' => 'ProjectController@editBudget'
                    ]
                );
                $router->post(
                    '/project/{project}/update-budget',
                    [
                        'as'   => 'project.budget.update',
                        'uses' => 'ProjectController@updateBudget'
                    ]
                );
                $router->post(
                    '/project/{project}/delete-budget/{index}',
                    [
                        'as'   => 'project.budget.destroy',
                        'uses' => 'ProjectController@deleteBudget'
                    ]
                );
                $router->post(
                    '/project/{project}/add-another-budget',
                    [
                        'as'   => 'project.add-another-budget',
                        'uses' => 'ProjectController@addAnotherBudget'
                    ]
                );
            }
        );
    }
);
