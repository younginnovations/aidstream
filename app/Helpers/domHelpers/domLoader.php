<?php

/**
 * Loads public style sheets according to the subdomain.
 *
 * @return string
 */
function publicStylesheet()
{
    $link           = "<link href='%s' rel='stylesheet'>";
    $baseStyleSheet = sprintf($link, asset('/css/style.min.css'));


    if (isTzSubDomain()) {
        $faviconLink    = sprintf('<link rel="shortcut icon" type="image/png" sizes="32*32" href="%s"/>', asset('/images/favicon-tz.png'));

        $styleSheet = sprintf($link, asset('/tz/css/tz.min.css'));

        return $faviconLink . $baseStyleSheet . $styleSheet;
    } elseif (isNpSubDomain()) {
        $faviconLink    = sprintf('<link rel="shortcut icon" type="image/png" sizes="32*32" href="%s"/>', asset('/images/np/favicon-np.pngs'));

        $styleSheet = sprintf($link, asset('/np/css/np.min.css'));
        return $faviconLink . $baseStyleSheet . $styleSheet;
    } else {
        $faviconLink = sprintf('<link rel="shortcut icon" type="image/png" sizes="32*32" href="%s"/>', '/images/favicon.png');

        return $faviconLink . $baseStyleSheet;
    }
}

/**
 * Loads style sheets for all authenticated routes according to the subdomain.
 *e
 * @return string
 */
function authStyleSheets()
{
    $link           = "<link href='%s' rel='stylesheet'>";
    $baseStyleSheet = sprintf($link, asset('/lite/css/lite.min.css'));
    $faviconLink = "<link rel='shortcut icon' type='image/png' sizes='32*32' href='%s'/>";

    if (isTzSubDomain()) {
        $styleSheet = sprintf($link, asset('/tz/css/tz.min.css'));
        $favicon = sprintf($faviconLink, asset('/images/favicon-tz.png'));

        return $favicon . $baseStyleSheet . $styleSheet;
    } else {
        $favicon = sprintf($faviconLink, asset('/images/favicon.png'));

        return $favicon . $baseStyleSheet;
    }
}
