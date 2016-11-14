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
$router->post(
    'hintStatus',
    [
        'as'   => 'hintStatus',
        'uses' => 'UserOnBoardingController@storeHintStatus'
    ]
);
$router->get(
    'incompleteStep',
    [
        'as'   => 'incompleteStep',
        'uses' => 'UserOnBoardingController@firstIncompleteStep'
    ]
);
$router->get(
    '/check-onboarding-step',
    [
        'as'   => 'check-onboarding-step',
        'uses' => 'UserOnBoardingController@checkOnboardingStep'
    ]
);
