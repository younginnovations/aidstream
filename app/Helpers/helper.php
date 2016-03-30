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
