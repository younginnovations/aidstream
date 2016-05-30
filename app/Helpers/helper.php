<?php
use App\Models\Activity\Activity;
use App\Models\Settings;
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
 * get default currency which is predefined under activity defaults
 * @return null
 */
function getDefaultCurrency()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_currency');
        }
    }

    $defaultCurrency = $defaultFieldValues ? $defaultFieldValues[0]['default_currency'] : null;

    return $defaultCurrency;
}

/**
 * get default language which is predefined under  activity defaults
 * @return null
 */
function getDefaultLanguage()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_language');
        }
    }
    $defaultLanguage = $defaultFieldValues ? $defaultFieldValues[0]['default_language'] : null;

    return $defaultLanguage;
}

/**
 * Get the required index from a nested array.
 * @param        $arr
 * @param        $arguments
 * @param string $default
 * @return string
 */
function getVal($arr, array $arguments, $default = "")
{
    if (!$arr) {
        return $default;
    }

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

/**
 * Checks if the request route contains prefix SuperAdmin.
 * @return bool
 */
function isSuperAdminRoute() {
    $routeAction = request()->route()->getAction();
    return isset($routeAction['SuperAdmin']);
}

/**
 * Check if the host contains a subdomain.
 * @return bool
 */
 function hasSubdomain()
{
    $host        = request()->getHost();
    $routePieces = explode('.', $host);

    return (count($routePieces) > 1);
}
