<?php

$router->get(
    'welcome',
    [
        'as'   => 'welcome',
        'uses' => 'UserOnBoardingController@welcome'
    ]
);

$router->get(
    'dashboardTour',
    [
        'as'   => 'dashboardTour',
        'uses' => 'UserOnBoardingController@startDashboardTour'
    ]
);

$router->post(
    'savePublisherAndApiId',
    [
        'as'   => 'savePublisherAndApiId',
        'uses' => 'UserOnBoardingController@storePublisherAndApiId'
    ]
);
$router->post(
    'savePublishingType',
    [
        'as'   => 'savePublishingType',
        'uses' => 'UserOnBoardingController@storePublishingType'
    ]
);
$router->post(
    'savePublishFiles',
    [
        'as'   => 'savePublishFiles',
        'uses' => 'UserOnBoardingController@storePublishFiles'
    ]
);
$router->post(
    'saveActivityElementsChecklist',
    [
        'as'   => 'saveActivityElementsChecklist',
        'uses' => 'UserOnBoardingController@storeActivityElementsChecklist'
    ]
);
$router->post(
    'saveDefaultValues',
    [
        'as'   => 'saveDefaultValues',
        'uses' => 'UserOnBoardingController@storeDefaultValues'
    ]
);
$router->get(
    'exploreLater',
    [
        'as'   => 'exploreLater',
        'uses' => 'UserOnBoardingController@exploreLater'
    ]
);
$router->post(
    'completeOnBoarding',
    [
        'as'   => 'completeOnBoarding',
        'uses' => 'UserOnBoardingController@completeOnBoarding'
    ]
);

$router->get(
    'continueExploring',
    [
        'as'   => 'continueExploring',
        'uses' => 'UserOnBoardingController@continueExploring'
    ]
);
