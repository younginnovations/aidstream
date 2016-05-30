<?php

$router->group(
    ['domain' => 'tz.' . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'Transaction'],
            function ($router) {
                $router->get(
                    '/project/{projectId}/transaction/{transactionType}/create',
                    [
                        'as'   => 'transaction.create',
                        'uses' => 'TransactionController@transactionCreate'
                    ]
                );
                $router->resource('transaction', 'TransactionController');
            }
        );
    }
);
