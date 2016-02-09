<?php namespace App\Migration;

use App\Models\Activity\Activity as ActivityModel;
use Illuminate\Database\DatabaseManager;


class ActivityData
{
    protected $activityModel;
    protected $mysqlConn;

    function __construct(ActivityModel $activityModel)
    {
        $this->activityModel = $activityModel;
    }

    public function getOrgIdAccountId()
    {
        $this->initDBConnection('mysql');
        $data = $this->mysqlConn->table('iati_organisation')
                                ->select('*')
                                ->get();

        return $data;
    }

    public function fetchActivityData($accountId)
    {
        $this->initDBConnection('mysql');
        // organisation id is foreign key
        $orgId              = $this->fetchOrgId($accountId);
        $iati_activities_id = $this->mysqlConn->table('iati_activities')
                                              ->select('id')
                                              ->where('account_id', '=', $accountId)
                                              ->first()->id;

        $iati_activity = $this->mysqlConn->table('iati_activity')
                                         ->select('id')
                                         ->where('activities_id', '=', $iati_activities_id)
                                         ->get();
        foreach ($iati_activity as $key => $value) {
            $title           = [];
            $activity_id     = $value->id;
            $idFromIatiTitle = $this->mysqlConn->table('iati_title')
                                               ->select('id')
                                               ->where('activity_id', '=', $activity_id)
                                               ->first()->id;
            $titleInfo       = $this->mysqlConn->table('iati_title/narrative')
                                               ->select('text', '@xml_lang as xml_lang')
                                               ->where('title_id', $idFromIatiTitle)
                                               ->get();
            //get lang from xml_lang code
            foreach ($titleInfo as $value) {
                //need to make this custom func
                $lang_from_query = $this->mysqlConn->table('Language')
                                                   ->select('Code')
                                                   ->where('id', '=', $value->xml_lang)
                                                   ->first()->Code;
                $title[]         = ['language' => $lang_from_query, 'narrative' => $value->text];
            }
            $titleJson = json_encode($title);
            //identifier
            $iatiIdentifierInfo = $this->mysqlConn->table('iati_identifier')
                                                  ->select('activity_identifier', 'text')
                                                  ->where('activity_id', '=', $activity_id)
                                                  ->first();
            //array of activity data
            $iatiIdentifier     = ['activity_identifier' => $iatiIdentifierInfo->activity_identifier, 'iati_identifier_text' => $iatiIdentifierInfo->text];
            $iatiIdentifierJson = json_encode($iatiIdentifier);

            $iatiActivityDatetime = $this->mysqlConn->table('iati_activity')
                                                    ->select('@last_updated_datetime as last_updated_datetime')
                                                    ->where('id', '=', $activity_id)
                                                    ->first()->last_updated_datetime;
            // array of activity_data
            $activity                  = $this->activityModel->firstOrNew(['id' => $activity_id]);
            $activity->id              = $activity_id;
            $activity->identifier      = $iatiIdentifierJson;
            $activity->title           = $titleJson;
            $activity->organization_id = $orgId;
            $activity->created_at      = $activity_updated_at = $iatiActivityDatetime;
            $save                      = $activity->save();
            if ($save) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getActivities(array $data)
    {
        $this->initDBConnection('mysql');

        $activities      = [];
        $IatiActivityIds = [];
        foreach ($data as $datum) {
            $account_id     = $datum->account_id;
            $org_id         = $datum->id;
            $IatiActivities = $this->mysqlConn->table('iati_activities')
                                              ->select('id')
                                              ->where('account_id', '=', $account_id)
                                              ->first();
            if ($IatiActivities) {
                $IatiActivityIds[$org_id] = $this->mysqlConn->table('iati_activity')
                                                            ->select('*')
                                                            ->where('activities_id', '=', $IatiActivities->id)
                                                            ->get();
            }
        }

        // dd($IatiActivityIds);
        return $IatiActivityIds;
    }

    public function getActivitiesFor($orgId)
    {
        $this->initDBConnection('mysql');

        $accountId = $this->mysqlConn->table('iati_organisation')
                                     ->select('account_id')
                                     ->where('id', '=', $orgId)
                                     ->first();

        $accountId = ($accountId) ? $accountId->account_id : null;

        $IatiActivitiesId = $this->mysqlConn->table('iati_activities')
                                            ->select('id')
                                            ->where('account_id', '=', $accountId)
                                            ->first();

        $IatiActivitiesId = ($IatiActivitiesId) ? $IatiActivitiesId->id : null;

        $IatiActivityCollection = $this->mysqlConn->table('iati_activity')
                                                  ->select('*')
                                                  ->where('activities_id', '=', $IatiActivitiesId)
                                                  ->get();

        return $IatiActivityCollection;
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}