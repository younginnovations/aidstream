<?php

function message($response)
{
    $code    = $response['code'][0];
    $version = session()->get('version');
    $message = trans($version . "/message");
    $code    = (is_array($message) ? $version : config('app.default_version_name')) . '/message.' . $code;

    return trans($code, $response['code'][1]);
}