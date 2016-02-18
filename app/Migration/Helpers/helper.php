<?php

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;

/**
 * Get Organization for an Account.
 * @param $accountId
 * @return mixed
 */
function getOrganizationFor($accountId)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table('iati_organisation')
                ->select('id')
                ->where('account_id', '=', $accountId)
                ->first();
}

/**
 * Get the Language code for a Language with the given id.
 * @param $languageId
 * @return string
 */
function getLanguageCodeFor($languageId)
{
    return ($language = app()->make(DatabaseManager::class)
                             ->connection('mysql')
                             ->table('Language')
                             ->select('Code')
                             ->where('id', '=', $languageId)
                             ->first()) ? $language->Code : '';
}

/**
 * Get an Activity Identifier object.
 * @param $activityId
 * @return mixed
 */
function getActivityIdentifier($activityId)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table('iati_identifier')
                ->select('activity_identifier', 'text')
                ->where('activity_id', '=', $activityId)
                ->first();
}

/**
 * Fetch Narratives from a given table.
 * @param $value
 * @param $table
 * @param $column
 * @return mixed
 */
function fetchNarratives($value, $table, $column)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*', '@xml_lang as xml_lang')
                ->where($column, '=', $value)
                ->get();
}

/**
 * Fetch any given field from any given table on the conditions specified.
 * @param $field
 * @param $table
 * @param $column
 * @param $value
 * @return Builder
 */
function getBuilderFor($field, $table, $column, $value)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select($field)
                ->where($column, '=', $value);
}

/**
 * Fetch code from a given table.
 * @param $id
 * @param $table
 * @param $act
 * @return string
 */
function fetchCode($id, $table, $act = null)
{
    return ($code = app()->make(DatabaseManager::class)
                         ->connection('mysql')
                         ->table($table)
                         ->select('Code')
                         ->where('id', '=', $id)
                         ->first()) ? $code->Code : '';
}

function fetchAnyNarratives($anyNarratives)
{
    $language  = "";
    $Narrative = [];
    foreach ($anyNarratives as $eachNarrative) {
        $narrativeText = $eachNarrative->text;
        if ($eachNarrative->xml_lang != "") {
            $language = getLanguageCodeFor($eachNarrative->xml_lang);
        }
        $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
    }
    // format incase of no narrative
    if (empty($anyNarratives)) {
        $narrative = [['narrative' => "", 'language' => ""]];
    } else {
        $narrative = $Narrative;
    }

    return $narrative;
}

/**
 * fetch data from a table
 * @param $table
 * @param $toCheckId
 * @param $id
 * @return mixed
 */
function fetchDataFrom($table, $toCheckId, $id)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*')
                ->where($toCheckId, '=', $id)
                ->get();
}

/**
 * fetch data from a table with code
 * @param $table
 * @param $toCheckId
 * @param $id
 * @return mixed
 */
function fetchDataWithCodeFrom($table, $toCheckId, $id)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*', '@code as code')
                ->where($toCheckId, '=', $id)
                ->get();

}
