<?php

$router->group(
    ['namespace' => 'Complete\Xml'],
    function ($router) {
        $router->get(
            '/xml-import',
            [
                'as'   => 'xml-import.index',
                'uses' => 'XmlImportController@index'
            ]
        );

        $router->post(
            'xml-import',
            [
                'as'   => 'xml-import.store',
                'uses' => 'XmlImportController@store'
            ]
        );

        $router->get(
            '/xml-import/import-status',
            [
                'as'   => 'xml-import.status',
                'uses' => 'XmlImportController@status'
            ]
        );

        $router->get(
            '/xml-import/isCompleted',
            [
                'as'   => 'xml-import.isCompleted',
                'uses' => 'XmlImportController@isCompleted'
            ]
        );

        $router->get(
            '/xml-import/complete',
            [
                'as'   => 'xml-import.complete',
                'uses' => 'XmlImportController@complete'
            ]
        );

        $router->get(
            '/xml-import/schemaErrors',
            [
                'as'   => 'xml-import.schemaErrors',
                'uses' => 'XmlImportController@schemaErrors'
            ]
        );

        $router->get(
            'xml-import/localisedText',
            [
                'as'   => 'xml-import.localisedText',
                'uses' => 'XmlImportController@getLocalisedXmlFile'
            ]
        );
    }
);
