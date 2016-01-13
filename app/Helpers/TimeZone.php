<?php

/**
 * Convert date and time according to local timezone selected
 * @param $currentTZ
 * @param $newTZ
 * @param $date
 * @return string
 */
function changeTimeZone($currentTZ, $newTZ, $date)
{
    $current = new \DateTimeZone($currentTZ);
    $new     = new \DateTimeZone($newTZ);
    $date    = new \DateTime($date, $current);
    $result  = $date->setTimezone($new);

    return $result->format('Y-m-d H:i:s');
}
