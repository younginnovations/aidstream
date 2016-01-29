<?php

/**
 * define the Url
 * @param $url
 * @return string
 */
function getLinkStatus($url)
{
    $currentUrl = url(Request::path());

    return $url === $currentUrl ? 'active' : '';
}
