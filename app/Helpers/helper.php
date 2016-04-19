<?php
use Illuminate\Database\DatabaseManager;

/**
 * removes empty values
 * @param $data
 */
function removeEmptyValues(&$data)
{
    foreach ($data as &$subData) {
        if (is_array($subData)) {
            removeEmptyValues($subData);
        }
    }
    $data = array_filter(
        $data,
        function ($value) {
            return ($value != '' && $value != []);
        }
    );
}

/**
 * trim an input
 * @param $input
 * @return string
 */
function trimInput($input)
{
    return trim(preg_replace('/\s+/', " ", $input));
}

/**
 * checks empty template or empty array
 * @param $input
 * @return bool
 */
function emptyOrHasEmptyTemplate($data)
{
    $temp = $data;
    removeEmptyValues($temp);

    return (!boolval($temp));
}

/**
 * get default currency which is predefined under settings
 * @return null
 */
function getDefaultCurrency()
{
    $defaultFieldValues = app()->make(Databasemanager::class)->table('settings')->select('default_field_values')->where('organization_id', '=', session('org_id'))->first();
    $defaultCurrency    = $defaultFieldValues ? json_decode($defaultFieldValues->default_field_values, true)[0]['default_currency'] : null;

    return $defaultCurrency;
}

/**
 * Get the required index from a nested array.
 * @param        $arr
 * @param        $arguments
 * @param string $default
 * @return string
 */
function getVal($arr, $arguments, $default = "")
{
    (!is_string($arguments)) ?: $arguments = explode('.', $arguments);
    if (is_array($arr)) {
        if (isset($arr[$arguments[0]]) && count(array_slice($arguments, 1)) === 0) {
            return $arr[$arguments[0]];
        } else {
            if (isset($arr[$arguments[0]]) && is_array($arr[$arguments[0]])) {
                $result = getVal($arr[$arguments[0]], array_slice($arguments, 1), $default);

                return $result ? $result : $default;
            } else {
                return $default;
            }
        }
    } else {
        if (isset($arr) && !is_array($arr)) {
            return $arr;
        } else {
            return $default;
        }
    }
}
