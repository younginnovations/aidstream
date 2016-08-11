<?php

function message($response)
{
    $code    = $response['code'][0];
    $version = session()->get('version');
    $message = trans($version . "/message");
    $code    = (is_array($message) ? $version : config('app.default_version_name')) . '/message.' . $code;

    if (array_key_exists(1, $response['code'])) {
        $result = $response['code'][1];
    } else {
        $result = $response['code'];
    }

    return trans($code, $result);
}

function help($code, $tooltip = true)
{
    $help = trans(session()->get('version') . "/help");
    is_array($help) ?: $help = trans(config('app.default_version_name') . '/help');
    if (!isset($help[$code])) {
        $code = 'no_help_text';
    }

    return htmlspecialchars($help[$code]);
}