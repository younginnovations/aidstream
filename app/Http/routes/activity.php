<?php

$router->group(
['namespace' => 'Complete\Activity'],
function ($router) {
$router->resource('activity', 'ActivityController');
}
);

