<?php namespace App\Migration;

use Illuminate\Database\DatabaseManager;

class MigrateHelper
{
    protected $mysqlConn;

    public function fetchOrgId($accountId)
    {
        $this->initDBConnection('mysql');

        $orgId = $this->mysqlConn->table('iati_organisation')
                                 ->select('id')
                                 ->where('account_id', '=', $accountId)
                                 ->first();

        if ($orgId) {
            $orgId = $orgId->id;

            return $orgId;
        }

        return null;
    }

    public function fetchActivity()
    {
        $this->initDBConnection('mysql');

        $accountId          = 557;
        $iati_activities_id = $this->mysqlConn->table('iati_activities')
                                              ->select('id')
                                              ->where('account_id', '=', $accountId)
                                              ->first()->id;

        $iati_activity = $this->mysqlConn->table('iati_activity')
                                         ->select('id')
                                         ->where('activities_id', '=', $iati_activities_id)
                                         ->get();

        return $iati_activity;
    }

    public function ActivityIdentifier($activity_id)
    {
        $this->initDBConnection('mysql');

        $iatiIdentifierInfo = $this->mysqlConn->table('iati_identifier')
                                              ->select('activity_identifier', 'text')
                                              ->where('activity_id', '=', $activity_id)
                                              ->first();

        // dd($iatiIdentifierInfo);
        return $iatiIdentifierInfo;
    }

    public function FetchLangCode($lang)
    {
        $this->initDBConnection('mysql');

        $code = $this->mysqlConn->table('Language')
                                ->select('Code')
                                ->where('id', '=', $lang)
                                ->first();

        if ($code) {
            return $code->Code;
        }

        return '';
    }

    public function fetchAccountId($orgId)
    {
        $this->initDBConnection('mysql');

        $account_id = $this->mysqlConn->table('iati_organisation')
                                      ->select('account_id')
                                      ->where('id', '=', $orgId)
                                      ->first();

        return $account_id->account_id;
    }

    public function FetchCurrencyCode($currency_code)
    {
        $this->initDBConnection('mysql');

        if ($currency_code) {
            $currency = $this->mysqlConn->table('Currency')
                                        ->select('Code')
                                        ->where('id', '=', $currency_code)
                                        ->first();

            return $currency->Code;
        }

        return '';
    }

    public function fetchCode($anyId, $table, $act)
    {
        $this->initDBConnection('mysql');

        if (!empty($anyId)) {
            $fetchCode = $this->mysqlConn->table($table)
                                         ->select('Code')
                                         ->where('id', '=', $anyId)
                                         ->first();
            $Code      = $fetchCode ? $fetchCode->Code : '';
        } else {
            $Code = "";
        }

        return $Code;
    }

    public function fetchNarratives($anyId, $table, $table_field_id)
    {
        $this->initDBConnection('mysql');

        $fieldNarratives = $this->mysqlConn->table($table)
                                           ->select('*', '@xml_lang as xml_lang')
                                           ->where($table_field_id, '=', $anyId)
                                           ->get();

        return $fieldNarratives;
    }

    public function FetchAidTypeCode($aidTypeId)
    {
        $this->initDBConnection('mysql');

        if ($aidTypeId) {
            $aidtypeCode = $this->mysqlConn->table('AidType')
                                           ->select('Code')
                                           ->where('id', '=', $aidTypeId)
                                           ->first();

            return $aidtypeCode->Code;
        }

        return '';
    }

    function fetchAnyField($field, $table, $tableField, $matchField)
    {
        $this->initDBConnection('mysql');

        $fetchField = $this->mysqlConn->table($table)
                                      ->select($field)
                                      ->where($tableField, '=', $matchField);

        return $fetchField;
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}