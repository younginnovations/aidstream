<?php namespace App\Migration;

use App\Migration\Elements\Title;
use App\Models\Activity\Activity as ActivityModel;
use App\Migration\ActivityData as ActivityData;
use Illuminate\Database\DatabaseManager;
use App\Migration\MigrateHelper;

class MigrateActivity
{
    protected $activityModel;
    protected $mysqlConn;
    protected $activityData;
    protected $title;
    protected $migrateHelper;
    protected $data;

    function __construct(ActivityModel $activityModel, DatabaseManager $databaseManager, ActivityData $activityData, Title $title, MigrateHelper $migrateHelper)
    {
        $this->mysqlConn     = $databaseManager->connection('mysql');
        $this->activityModel = $activityModel;
        $this->activityData  = $activityData;
        $this->title         = $title;
        $this->data          = [];
        $this->migrateHelper = $migrateHelper;
    }

    /**
     * Fetch Title data for a particular Organization.
     * @param $orgId
     */
    public function fetchActivityData($orgId)
    {
        $this->data = [];
        $activities = $this->activityData->getActivitiesFor($orgId); // for 1 org
        foreach ($activities as $activity) {
            $activityId                                 = $activity->id;
            $this->data[$activityId]['organization_id'] = $orgId;
            $this->data[$activityId]['id']              = $activityId;

            $this->titleDataFetch($activityId)
                 ->fetchIdentifier($activityId)
                 ->fetchOtherIdentifier($activityId)
                 ->fetchDescription($activityId)
                 ->fetchActivityStatus($activityId)
                 ->fetchActivityDate($activityId)
                 ->fetchParticipatingOrganization($activityId)
                 ->fetchRecipientCountry($activityId);
        }

        return $this->data;
    }

    public function titleDataFetch($activityId)
    {
        $formattedData = [];
        $activityData  = [];

        $iatiTitle = $this->mysqlConn->table('iati_title')
                                     ->select('id')
                                     ->where('activity_id', '=', $activityId)
                                     ->first();

        if ($iatiTitle) {
            $titleInfo = $this->mysqlConn->table('iati_title/narrative')
                                         ->select('text', '@xml_lang as xml_lang')
                                         ->where('title_id', '=', $iatiTitle->id)
                                         ->get();
            //get lang from xml_lang code
            $lang_from_query = [];
            foreach ($titleInfo as $title) {
                $lang = $title->xml_lang;
                if (!empty($lang)) {
                    $lang_from_query[] = $this->mysqlConn->table('Language')
                                                         ->select('Code')
                                                         ->where('id', '=', $lang)
                                                         ->first()->Code;
                } else {
                    $lang_from_query = '';
                }
                $activityData[$activityId] = ['title' => $titleInfo, 'lang' => $lang_from_query];
            }
        } else {
            $activityData[$activityId] = ['title' => '', 'lang' => ''];
        }
        $formattedData                    = $this->title->format($activityData);
        $this->data[$activityId]['title'] = $formattedData;

        return $this;
    }

    public function fetchIdentifier($activityId)
    {
        $iatiIdentifierInfo = $this->mysqlConn->table('iati_identifier')
                                              ->select('activity_identifier', 'text')
                                              ->where('activity_id', '=', $activityId)
                                              ->first();
        //array of activity data
        $this->data[$activityId]['identifier'] = ['activity_identifier' => $iatiIdentifierInfo->activity_identifier, 'iati_identifier_text' => $iatiIdentifierInfo->text];

        return $this;
    }

    public function fetchOtherIdentifier($activityId)
    {
        $iatiOtherInfo = $this->mysqlConn->table('iati_other_identifier')
                                         ->select('@ref as ref', '@type as type', 'id')
                                         ->where('activity_id', '=', $activityId)
                                         ->first();

        if (is_null($iatiOtherInfo) == false) {

            $type_id                     = $iatiOtherInfo->type;
            $type_code                   = $this->mysqlConn->table('OtherIdentifierType')
                                                           ->select('Code')
                                                           ->where('id', '=', $type_id)
                                                           ->first();
            $iatiOtherIdentifierOwnerOrg = $this->mysqlConn->table('iati_other_identifier/ownerorg')
                                                           ->select('id', '@ref as owner_org_ref')
                                                           ->where('other_activity_identifier_id', '=', $iatiOtherInfo->id)
                                                           ->first();
            if (is_null($iatiOtherIdentifierOwnerOrg) == false) {
                $ownerOrgReference            = $iatiOtherIdentifierOwnerOrg->owner_org_ref;
                $id_owner_org                 = $iatiOtherIdentifierOwnerOrg->id;
                $iatiOtherIdentifierNarrative = $this->mysqlConn->table('iati_other_identifier/ownerorg/narrative')
                                                                ->select('text', '@xml_lang as xml_lang')
                                                                ->where('owner_org_id', '=', $id_owner_org)
                                                                ->get();
                $narrativeArray               = [];
                foreach ($iatiOtherIdentifierNarrative as $eachNarrative) {
                    $lang_id          = $eachNarrative->xml_lang;
                    $lang_code        = $this->migrateHelper->FetchLangCode($lang_id);
                    $narrativeArray[] = ['narrative' => $eachNarrative->text, 'language' => $lang_code];
                }
                $ownerOrgArray                               = ['reference' => $ownerOrgReference, 'narrative' => $narrativeArray];
                $this->data[$activityId]['other_identifier'] = ['reference' => $iatiOtherInfo->ref, 'type' => $type_code->Code, 'owner_org' => $ownerOrgArray];
            }
        }

        return $this;
    }

    public function fetchDescription($activityId)
    {
        $description     = $this->mysqlConn->table('iati_description')
                                           ->select('id', '@type as type')
                                           ->where('activity_id', '=', $activityId)
                                           ->get();
        $dataDescription = null;
        foreach ($description as $des) {
            $language = "";
            $typeCode = "";
            $descType = $this->mysqlConn->table('iati_description')
                                        ->select('@type as type')
                                        ->where('id', '=', $des->id)
                                        ->first();
            $typeId   = $descType->type;
            if ($typeId != "") {
                $typeCode = $this->mysqlConn->table('DescriptionType')
                                            ->select('Code')
                                            ->where('id', '=', $typeId)
                                            ->first()->Code;
            }
            $descriptionNarratives = $this->mysqlConn->table('iati_description/narrative')
                                                     ->select('*', '@xml_lang as xml_lang_id')
                                                     ->where('description_id', '=', $des->id)//
                                                     ->get();
            $dataNarrative         = [];
            foreach ($descriptionNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;
                if ($eachNarrative->xml_lang_id != "") {
                    $language = $this->migrateHelper->FetchLangCode($eachNarrative->xml_lang_id);
                }
                $dataNarrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }
            $dataDescription[] = ['type' => $typeCode, 'narrative' => $dataNarrative];
        }
        if (!is_null($description)) {
            $this->data[$activityId]['description'] = $dataDescription;
        }

        return $this;
    }

    public function fetchActivityStatus($activityId)
    {
        $activity_status = $this->mysqlConn->table('iati_activity_status')
                                           ->select('@code as code')
                                           ->where('activity_id', '=', $activityId)
                                           ->first();
        $activityStatus  = null;
        if (!is_null($activity_status)) {
            $activityStatus = $activity_status->code;
        }
        $this->data[$activityId]['activity_status'] = $activityStatus;

        return $this;
    }

    public function fetchActivityDate($activityId)
    {
        $dataActivityDate        = null;
        $language                = "";
        $activity_date_instances = $this->mysqlConn->table('iati_activity_date')
                                                   ->select('*', '@iso_date as iso_date', '@type as type')
                                                   ->where('activity_id', '=', $activityId)
                                                   ->get();

        foreach ($activity_date_instances as $dateInfo) {
            $isoDate                   = $dateInfo->iso_date;
            $ActivityDateTypeId        = $dateInfo->type;
            $FetchActivityDateTypeCode = $this->mysqlConn->table('ActivityDateType')
                                                         ->select('Code')
                                                         ->where('id', '=', $ActivityDateTypeId)
                                                         ->first();
            $ActivityDateTypeCode      = $FetchActivityDateTypeCode->Code;
            $dateNarratives            = $this->mysqlConn->table('iati_activity_date/narrative')
                                                         ->select('*', '@xml_lang as xml_lang')
                                                         ->where('activity_date_id', '=', $dateInfo->id)
                                                         ->get();
            $Narrative                 = [];
            foreach ($dateNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;
                if ($eachNarrative->xml_lang != "") {
                    $language = $this->migrateHelper->FetchLangCode($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            // format incase of no narrative
            if (empty($dateNarratives)) {
                $narrative = ['narrative' => "", 'language' => ""];
            } else {
                $narrative = $Narrative;
            }
            $dataActivityDate[] = ['date' => $isoDate, 'type' => $ActivityDateTypeCode, 'narrative' => $narrative];
        }
        if (!is_null($activity_date_instances)) {
            $this->data[$activityId]['activity_date'] = $dataActivityDate;
        }

        return $this;
    }

    public function fetchParticipatingOrganization($activityId)
    {
        $dataParticipatingOrg        = null;
        $language                    = "";
        $participating_org_instances = $this->mysqlConn->table('iati_participating_org')
                                                       ->select('@role as role', '@type as type', '@ref as ref', 'id', 'activity_id')
                                                       ->where('activity_id', '=', $activityId)
                                                       ->get();

        foreach ($participating_org_instances as $participatingOrgInfo) {
            $OrgType_Id = $participatingOrgInfo->type;
            if ($OrgType_Id != "") {
                $fetchOrgTypeCode = $this->mysqlConn->table('OrganisationType')
                                                    ->select('Code')
                                                    ->where('id', '=', $OrgType_Id)
                                                    ->first();
                $OrgTypeCode      = $fetchOrgTypeCode->Code;
            } else {
                $OrgTypeCode = null;
            }
            $OrgType_Id                 = $participatingOrgInfo->type;
            $Identifier                 = $participatingOrgInfo->ref;
            $OrgRoleId                  = $participatingOrgInfo->role;
            $FetchOrgRoleCode           = $this->mysqlConn->table('OrganisationRole')
                                                          ->select('Code')
                                                          ->where('id', '=', $OrgRoleId)
                                                          ->first();
            $OrgRoleCode                = $FetchOrgRoleCode->Code;
            $ParticipatingOrgNarratives = $this->mysqlConn->table('iati_participating_org/narrative')->select('*', '@xml_lang as xml_lang')
                                                          ->where('participating_org_id', '=', $participatingOrgInfo->id)
                                                          ->get();

            $Narrative = [];
            foreach ($ParticipatingOrgNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;
                if ($eachNarrative->xml_lang != "") {
                    $language = $this->migrateHelper->FetchLangCode($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }
            if (empty($ParticipatingOrgNarratives)) {  // format incase of no narrative
                $narrative = ['narrative' => "", 'language' => ""];
            } else {
                $narrative = $Narrative;
            }
            $dataParticipatingOrg[] = ['organization_role' => $OrgRoleCode, 'identifier' => $Identifier, 'organization_type' => $OrgTypeCode, 'narrative' => $narrative];
        }
        if (!is_null($participating_org_instances)) {
            $this->data[$activityId]['participating_organization'] = $dataParticipatingOrg;
        }

        return $this;
    }

    public function fetchRecipientCountry($activityId)
    {
        $dataRecipientCountry  = null;
        $language              = "";
        $recipientOrgInstances = $this->mysqlConn->table('iati_recipient_country')
                                                 ->select('*', '@code as code', '@percentage as percentage')
                                                 ->where('activity_id', '=', $activityId)
                                                 ->get();

        foreach ($recipientOrgInstances as $recipientOrgInfo) {
            $recipientCountryId   = $recipientOrgInfo->code;
            $recipientCountryCode = $this->mysqlConn->table('Country')
                                                    ->select('Code')
                                                    ->where('id', '=', $recipientCountryId)
                                                    ->first();

            $countryCode                = $recipientCountryCode->Code;
            $countryPercentage          = $recipientOrgInfo->percentage;
            $recipientCountryNarratives = $this->mysqlConn->table('iati_recipient_country/narrative')
                                                          ->select('*', '@xml_lang as xml_lang')
                                                          ->where('recipient_country_id', '=', $recipientOrgInfo->id)
                                                          ->get(); //Can be many
            $Narrative                  = [];

            foreach ($recipientCountryNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = $this->migrateHelper->FetchLangCode($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            // format incase of no narrative
            if (empty($recipientCountryNarratives)) {
                $narrative = ['narrative' => "", 'language' => ""];
            } else {
                $narrative = $Narrative;
            }
            $dataRecipientCountry[] = ['country_code' => $countryCode, 'percentage' => $countryPercentage, 'narrative' => $narrative];
        }
        if (!is_null($recipientOrgInstances)) {
            $this->data[$activityId]['recipient_country'] = $dataRecipientCountry;
        }

        return $this;
    }
}
