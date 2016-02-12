<?php namespace App\Migration\Migrator\Data;

use App\Migration\ActivityData;
use App\Migration\Elements\ActivityDate;
use App\Migration\Elements\Description;
use App\Migration\Elements\Identifier;
use App\Migration\Elements\OtherIdentifier;
use App\Migration\Elements\ParticipatingOrganization;
use App\Migration\Elements\RecipientCountry;
use App\Migration\Elements\RecipientRegion;
use App\Migration\Elements\Sector;
use App\Migration\Elements\Title;

/**
 * Class ActivityQuery
 * @package App\Migration\Migrator\Data
 */
class ActivityQuery extends Query
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ActivityData
     */
    protected $activityData;
    /**
     * @var Title
     */
    protected $title;

    /**
     * @var Identifier
     */
    protected $identifier;

    /**
     * @var OtherIdentifier
     */
    protected $otherIdentifier;

    /**
     * @var Description
     */
    protected $description;

    /**
     * @var ActivityDate
     */
    protected $activityDate;

    /**
     * @var ParticipatingOrganization
     */
    protected $participatingOrganization;

    /**
     * @var RecipientCountry
     */
    protected $recipientCountry;

    /**
     * @var RecipientRegion
     */
    protected $recipientRegion;

    /**
     * @var Sector
     */
    protected $sector;

    /**
     * ActivityQuery constructor.
     * @param ActivityData              $activityData
     * @param Title                     $title
     * @param Identifier                $identifier
     * @param OtherIdentifier           $otherIdentifier
     * @param Description               $description
     * @param ActivityDate              $activityDate
     * @param ParticipatingOrganization $participatingOrganization
     * @param RecipientCountry          $recipientCountry
     * @param RecipientRegion           $recipientRegion
     * @param Sector                    $sector
     */
    public function __construct(
        ActivityData $activityData,
        Title $title,
        Identifier $identifier,
        OtherIdentifier $otherIdentifier,
        Description $description,
        ActivityDate $activityDate,
        ParticipatingOrganization $participatingOrganization,
        RecipientCountry $recipientCountry,
        RecipientRegion $recipientRegion,
        Sector $sector
    ) {
        $this->activityData              = $activityData;
        $this->title                     = $title;
        $this->identifier                = $identifier;
        $this->otherIdentifier           = $otherIdentifier;
        $this->description               = $description;
        $this->activityDate              = $activityDate;
        $this->participatingOrganization = $participatingOrganization;
        $this->recipientCountry          = $recipientCountry;
        $this->recipientRegion           = $recipientRegion;
        $this->sector                    = $sector;
    }

    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $data = [];
        $this->initDBConnection();

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function getData($organizationId, $accountId)
    {
        $activities = $this->activityData->getActivitiesFor($organizationId);
        $this->data = [];

        foreach ($activities as $activity) {
            $activityId                                 = $activity->id;
            $this->data[$activityId]['organization_id'] = $accountId;
            $this->data[$activityId]['id']              = $activityId;

            $this->titleDataFetch($activityId)
                 ->fetchIdentifier($activityId)
                 ->fetchOtherIdentifier($activityId)
                 ->fetchDescription($activityId)
                 ->fetchActivityStatus($activityId)
                 ->fetchActivityDate($activityId)
                 ->fetchParticipatingOrganization($activityId)
                 ->fetchRecipientCountry($activityId)
                 ->fetchRecipientRegion($activityId)
                 ->fetchSector($activityId);
        }

        return $this->data;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function titleDataFetch($activityId)
    {
        $activityData              = [];
        $activityData[$activityId] = ['title' => '', 'lang' => ''];

        $iatiTitle = $this->connection->table('iati_title')
                                      ->select('id')
                                      ->where('activity_id', '=', $activityId)
                                      ->first();

        if ($iatiTitle) {
            $titleInfo = $this->connection->table('iati_title/narrative')
                                          ->select('text', '@xml_lang as xml_lang')
                                          ->where('title_id', '=', $iatiTitle->id)
                                          ->get();

            //get lang from xml_lang code
            $lang_from_query = [];

            foreach ($titleInfo as $title) {
                $lang              = $title->xml_lang;
                $lang_from_query[] = getLanguageCodeFor($lang);

                $activityData[$activityId] = ['title' => $titleInfo, 'lang' => $lang_from_query];
            }
        }

        $formattedData                    = $this->title->format($activityData);
        $this->data[$activityId]['title'] = $formattedData;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchIdentifier($activityId)
    {
        $iatiIdentifierInfo = $this->connection->table('iati_identifier')
                                               ->select('activity_identifier', 'text')
                                               ->where('activity_id', '=', $activityId)
                                               ->first();

        //array of activity data
        $this->data[$activityId]['identifier'] = $this->identifier->format($iatiIdentifierInfo);

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchOtherIdentifier($activityId)
    {
        $iatiOtherInfo = $this->connection->table('iati_other_identifier')
                                          ->select('@ref as ref', '@type as type', 'id')
                                          ->where('activity_id', '=', $activityId)
                                          ->first();

        if (!is_null($iatiOtherInfo)) {
            $type_id   = $iatiOtherInfo->type;
            $type_code = $this->connection->table('OtherIdentifierType')
                                          ->select('Code')
                                          ->where('id', '=', $type_id)
                                          ->first();

            $iatiOtherIdentifierOwnerOrg = $this->connection->table('iati_other_identifier/ownerorg')
                                                            ->select('id', '@ref as owner_org_ref')
                                                            ->where('other_activity_identifier_id', '=', $iatiOtherInfo->id)
                                                            ->first();

            if (!is_null($iatiOtherIdentifierOwnerOrg)) {
                $ownerOrgReference = $iatiOtherIdentifierOwnerOrg->owner_org_ref;
                $id_owner_org      = $iatiOtherIdentifierOwnerOrg->id;

                $iatiOtherIdentifierNarrative = $this->connection->table('iati_other_identifier/ownerorg/narrative')
                                                                 ->select('text', '@xml_lang as xml_lang')
                                                                 ->where('owner_org_id', '=', $id_owner_org)
                                                                 ->get();
                $narrativeArray               = [
                    [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ];

                if ($iatiOtherIdentifierNarrative) {
                    foreach ($iatiOtherIdentifierNarrative as $eachNarrative) {
                        $lang_id          = $eachNarrative->xml_lang;
                        $lang_code        = getLanguageCodeFor($lang_id);
                        $narrativeArray[] = ['narrative' => $eachNarrative->text, 'language' => $lang_code];
                    }
                }

                $otherIdentifierData = [
                    'ownerOrgReference' => $ownerOrgReference,
                    'narratives'        => $narrativeArray,
                    'iatiOtherInfo'     => $iatiOtherInfo,
                    'typeCode'          => $type_code
                ];

                $this->data[$activityId]['other_identifier'] = $this->otherIdentifier->format($otherIdentifierData);
            }
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchDescription($activityId)
    {
        $descriptions    = $this->connection->table('iati_description')
                                            ->select('id', '@type as type')
                                            ->where('activity_id', '=', $activityId)
                                            ->get();
        $dataDescription = null;

        foreach ($descriptions as $description) {
            $language = "";
            $typeCode = "";
            $descType = $this->connection->table('iati_description')
                                         ->select('@type as type')
                                         ->where('id', '=', $description->id)
                                         ->first();
            $typeId   = $descType->type;

            if ($typeId != "") {
                $typeCode = ($descriptionType = $this->connection->table('DescriptionType')
                                                                 ->select('Code')
                                                                 ->where('id', '=', $typeId)
                                                                 ->first()) ? $descriptionType->Code : '';
            }

            $descriptionNarratives = $this->connection->table('iati_description/narrative')
                                                      ->select('*', '@xml_lang as xml_lang_id')
                                                      ->where('description_id', '=', $description->id)
                                                      ->get();
            $dataNarrative         = [];

            foreach ($descriptionNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;

                if ($eachNarrative->xml_lang_id != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang_id);
                }
                $dataNarrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            $dataDescription[] = $this->description->format(['code' => $typeCode, 'narrative' => $dataNarrative]);
        }

        if (!is_null($descriptions)) {
            $this->data[$activityId]['description'] = $dataDescription;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchActivityStatus($activityId)
    {
        $activity_status = $this->connection->table('iati_activity_status')
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

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchActivityDate($activityId)
    {
        $dataActivityDate        = null;
        $language                = "";
        $activity_date_instances = $this->connection->table('iati_activity_date')
                                                    ->select('*', '@iso_date as iso_date', '@type as type')
                                                    ->where('activity_id', '=', $activityId)
                                                    ->get();

        foreach ($activity_date_instances as $dateInfo) {
            $isoDate            = $dateInfo->iso_date;
            $ActivityDateTypeId = $dateInfo->type;

            $ActivityDateTypeCode = ($FetchActivityDateTypeCode = $this->connection->table('ActivityDateType')
                                                                                   ->select('Code')
                                                                                   ->where('id', '=', $ActivityDateTypeId)
                                                                                   ->first()) ? $FetchActivityDateTypeCode->Code : '';

            $dateNarratives = $this->connection->table('iati_activity_date/narrative')
                                               ->select('*', '@xml_lang as xml_lang')
                                               ->where('activity_date_id', '=', $dateInfo->id)
                                               ->get();
            $Narrative      = [];

            foreach ($dateNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            // format incase of no narrative
            if (empty($dateNarratives)) {
                $narrative = [['narrative' => "", 'language' => ""]];
            } else {
                $narrative = $Narrative;
            }

            $dataActivityDate[] = $this->activityDate->format($isoDate, $ActivityDateTypeCode, $narrative);
        }

        if (!is_null($activity_date_instances)) {
            $this->data[$activityId]['activity_date'] = $dataActivityDate;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchParticipatingOrganization($activityId)
    {
        $dataParticipatingOrg = null;
        $language             = "";

        $participating_org_instances = $this->connection->table('iati_participating_org')
                                                        ->select('@role as role', '@type as type', '@ref as ref', 'id', 'activity_id')
                                                        ->where('activity_id', '=', $activityId)
                                                        ->get();

        foreach ($participating_org_instances as $participatingOrgInfo) {
            $OrgType_Id = $participatingOrgInfo->type;

            if ($OrgType_Id != "") {
                $OrgTypeCode = ($fetchOrgTypeCode = $this->connection->table('OrganisationType')
                                                                     ->select('Code')
                                                                     ->where('id', '=', $OrgType_Id)
                                                                     ->first()) ? $fetchOrgTypeCode->Code : '';
            } else {
                $OrgTypeCode = '';
            }

            $OrgType_Id = $participatingOrgInfo->type;

            $Identifier       = $participatingOrgInfo->ref;
            $OrgRoleId        = $participatingOrgInfo->role;
            $FetchOrgRoleCode = $this->connection->table('OrganisationRole')
                                                 ->select('Code')
                                                 ->where('id', '=', $OrgRoleId)
                                                 ->first();

            $OrgRoleCode = $FetchOrgRoleCode ? $FetchOrgRoleCode->Code : '';

            $ParticipatingOrgNarratives = $this->connection->table('iati_participating_org/narrative')->select('*', '@xml_lang as xml_lang')
                                                           ->where('participating_org_id', '=', $participatingOrgInfo->id)
                                                           ->get();

            $Narrative = [];
            foreach ($ParticipatingOrgNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataParticipatingOrg[] = $this->participatingOrganization->format($ParticipatingOrgNarratives, $OrgRoleCode, $Identifier, $OrgTypeCode, $Narrative);
        }

        if (!is_null($participating_org_instances)) {
            $this->data[$activityId]['participating_organization'] = $dataParticipatingOrg;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchRecipientCountry($activityId)
    {
        $dataRecipientCountry  = null;
        $language              = "";
        $recipientOrgInstances = $this->connection->table('iati_recipient_country')
                                                  ->select('*', '@code as code', '@percentage as percentage')
                                                  ->where('activity_id', '=', $activityId)
                                                  ->get();

        foreach ($recipientOrgInstances as $recipientOrgInfo) {
            $recipientCountryId = $recipientOrgInfo->code;

            $recipientCountryCode = $this->connection->table('Country')
                                                     ->select('Code')
                                                     ->where('id', '=', $recipientCountryId)
                                                     ->first();

            $countryCode       = $recipientCountryCode ? $recipientCountryCode->Code : '';
            $countryPercentage = $recipientOrgInfo ? $recipientOrgInfo->percentage : '';

            $recipientCountryNarratives = $this->connection->table('iati_recipient_country/narrative')
                                                           ->select('*', '@xml_lang as xml_lang')
                                                           ->where('recipient_country_id', '=', $recipientOrgInfo->id)
                                                           ->get(); //Can be many
            $Narrative                  = [];

            foreach ($recipientCountryNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataRecipientCountry[] = $this->recipientCountry->format($countryCode, $countryPercentage, $Narrative, $recipientCountryNarratives);
        }

        if (!is_null($recipientOrgInstances)) {
            $this->data[$activityId]['recipient_country'] = $dataRecipientCountry;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchRecipientRegion($activityId)
    {
        $dataRecipientRegion      = null;
        $language                 = "";
        $recipientRegionInstances = $this->connection->table('iati_recipient_region')
                                                     ->select('*', '@code as code', '@percentage as percentage', '@vocabulary as vocabulary')
                                                     ->where('activity_id', '=', $activityId)
                                                     ->get();

        foreach ($recipientRegionInstances as $recipientRegionInfo) {
            $regionId           = $recipientRegionInfo->code;
            $regionVocabularyId = $recipientRegionInfo->vocabulary;
            $regionPercentage   = $recipientRegionInfo->percentage;

            $fetchRegionCode = $this->connection->table('Region')
                                                ->select('Code')
                                                ->where('id', '=', $regionId)
                                                ->first();

            $regionCode = $fetchRegionCode ? $fetchRegionCode->Code : '';

            $fetchRegionVocabularyCode = $this->connection->table('RegionVocabulary')
                                                          ->select('Code')
                                                          ->where('id', '=', $regionVocabularyId)
                                                          ->first();

            $regionVocabularyCode       = $fetchRegionVocabularyCode ? $fetchRegionVocabularyCode->Code : '';
            $recipientRegionId          = $recipientRegionInfo ? $recipientRegionInfo->id : '';
            $recipientCountryNarratives = $this->connection->table('iati_recipient_region/narrative')
                                                           ->select('*', '@xml_lang as xml_lang')
                                                           ->where('recipient_region_id', '=', $recipientRegionId)
                                                           ->get();

            $Narrative = [];

            foreach ($recipientCountryNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;
                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataRecipientRegion[] = $this->recipientRegion->format($regionCode, $regionVocabularyCode, $regionPercentage, $Narrative, $recipientCountryNarratives);
        }

        if (!is_null($recipientRegionInstances)) {
            $this->data[$activityId]['recipient_region'] = $dataRecipientRegion;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchSector($activityId)
    {
        $dataSector      = null;
        $language        = "";
        $sectorCode      = "";
        $sectorInstances = $this->connection->table('iati_sector')
                                            ->select('*', '@vocabulary as vocabulary', '@code as code', '@percentage as percentage')
                                            ->where('activity_id', '=', $activityId)
                                            ->get();

        foreach ($sectorInstances as $sectorInfo) {
            $sector_code  = $sector_category_code = $sector_text = "";  // initially null
            $vocabId      = $sectorInfo->vocabulary;
            $vocabCode    = fetchCode($vocabId, 'SectorVocabulary', $activityId);
            $sectorCodeId = $sectorInfo->code;
            $percentage   = $sectorInfo->percentage;

            if (!is_null($vocabId)) {
                $sectorCode = fetchCode($vocabId, 'Sector', $activityId);
            }

            $sectorNarratives = fetchNarratives($sectorInfo->id, 'iati_sector/narrative', 'sector_id');
            $Narrative        = [['narrative' => "", 'language' => ""]];

            foreach ($sectorNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataSector[] = $this->sector->format($vocabCode, $sector_code, $sector_category_code, $sector_text, $percentage, $Narrative, $sectorCode, $sectorCodeId, $sectorNarratives);
        }

        if (!is_null($sectorInstances)) {
            $this->data[$activityId]['sector'] = $dataSector;
        }

        return $this;
    }
}
