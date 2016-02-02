<?php namespace App\Migration;

use DB;
class MigrateHelper 
{
    protected $mysqlConn;
    function __construct() {
        $this->mysqlConn = DB::connection('mysql');
    }

    public function fetchOrgId($accountId)
    {
        $orgId = $this->mysqlConn->table('iati_organisation')
                                 ->select('id')
                                 ->where('account_id', '=',$accountId)
                                 ->first()->id;
        return $orgId;
    }

    public function fetchActivity()
    {
        $accountId = 557;
        $iati_activities_id = $this->mysqlConn->table('iati_activities')
                                              ->select('id')
                                              ->where('account_id', '=', $accountId)
                                              ->first()->id;

        $iati_activity = $this->mysqlConn->table('iati_activity')
                                         ->select('id')
                                         ->where('activities_id', '=',$iati_activities_id)
                                         ->get();

        return $iati_activity;
    }

    public function ActivityIdentifier($activity_id) {
        $iatiIdentifierInfo = $this->mysqlConn->table('iati_identifier')
                                              ->select('activity_identifier','text')
                                              ->where('activity_id','=',$activity_id)
                                              ->first();
       // dd($iatiIdentifierInfo);
        return $iatiIdentifierInfo;
    }

    public function FetchLangCode($lang) {
       $code =  $this->mysqlConn->table('Language')
                        ->select('Code')
                        ->where('id', '=', $lang)
                        ->first()->Code;
        return $code;
    }

    public function fetchAccountId($orgId)
    {
        $account_id = $this->mysqlConn->table('iati_organisation')
                                      ->select('account_id')
                                      ->where('id','=',$orgId)
                                      ->first();
        return $account_id->account_id;
    }

    public function FetchCurrencyCode($currency_code) {
        if ($currency_code) {
            $currency = $this->mysqlConn->table('Currency')
                                        ->select('Code')
                                        ->where('id','=',$currency_code)
                                        ->first();

            return $currency->Code;
        }

        return '';
    }

    public function FetchAidTypeCode($aidTypeId)
    {
        if ($aidTypeId) {
            $aidtypeCode  = $this->mysqlConn->table('AidType')
                                            ->select('Code')
                                            ->where('id','=',$aidTypeId)
                                            ->first();

            return $aidtypeCode->Code;
        }

        return '';
    }
}