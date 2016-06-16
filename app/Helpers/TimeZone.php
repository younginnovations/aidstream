<?php

/**
 * Convert date and time according to local timezone selected
 * @param        $date
 * @param string $format
 * @return string
 */
function changeTimeZone($date, $format = 'M d, Y H:i:s')
{
    $currentTZ = config('app.timezone');
    $newTZ     = Auth::user()->time_zone;
    $current   = new \DateTimeZone($currentTZ);
    $new       = new \DateTimeZone($newTZ);
    $date      = new \DateTime($date, $current);
    $result    = $date->setTimezone($new);

    return $result->format($format);
}

/**
 * convert date format
 * @param        $date
 * @param string $format
 * @return bool|string
 */
function formatDate($date, $format = 'F d, Y')
{
    if ($date != "") {
        return date($format, strtotime($date));
    }

    return "";
}
