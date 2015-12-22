<?php namespace App\Core\V201\Repositories\Activity;

use App\Core\Elements\CsvReader;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;

/**
 * Class UploadActivity
 * @package App\Core\V201\Repositories\Activity
 */
class UploadActivity
{
    protected $readCsv;
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;
    /**
     * @var OrganizationRepository
     */
    protected $orgRepo;
    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @param Activity               $activity
     * @param ActivityRepository     $activityRepo
     * @param CsvReader              $readCsv
     * @param OrganizationRepository $orgRepo
     * @param Organization           $organization
     */
    function __construct(Activity $activity, ActivityRepository $activityRepo, CsvReader $readCsv, OrganizationRepository $orgRepo, Organization $organization)
    {
        $this->readCsv      = $readCsv;
        $this->activity     = $activity;
        $this->activityRepo = $activityRepo;
        $this->orgRepo      = $orgRepo;
        $this->organization = $organization;
    }

    /**
     * upload activities
     * @param array $activityDetails
     * @param       $organization
     */
    public function upload(array $activityDetails, $organization)
    {
        $row      = [
            'identifier'                 => $activityDetails['identifier'],
            'title'                      => $activityDetails['title'],
            'description'                => $activityDetails['description'],
            'activity_status'            => $activityDetails['activity_status'],
            'participating_organization' => $activityDetails['participating_organization'],
            'recipient_country'          => $activityDetails['recipient_country'],
            'recipient_region'           => $activityDetails['recipient_region'],
            'sector'                     => $activityDetails['sector'],
            'activity_date'              => $activityDetails['activity_date'],
        ];
        $activity = $this->activity->newInstance($row);
        $organization->activities()->save($activity);
    }

    /**
     * update activity details
     * @param array $activityDetails
     * @param       $activityId
     */
    public function update(array $activityDetails, $activityId)
    {
        $activity                             = $this->activityRepo->getActivityData($activityId);
        $activity->identifier                 = $activityDetails['identifier'];
        $activity->title                      = $activityDetails['title'];
        $activity->description                = $activityDetails['description'];
        $activity->activity_status            = $activityDetails['activity_status'];
        $activity->participating_organization = $activityDetails['participating_organization'];
        $activity->recipient_country          = $activityDetails['recipient_country'];
        $activity->recipient_region           = $activityDetails['recipient_region'];
        $activity->sector                     = $activityDetails['sector'];
        $activity->activity_date              = $activityDetails['activity_date'];
        $activity->save();
    }

    /**
     * prepare activity array for upload
     * @param $activityRow
     * @param $orgId
     * @return array
     */
    public function formatFromExcelRow($activityRow, $orgId)
    {
        $activity         = [];
        $identifier       = $this->formatIdentifier($activityRow, $orgId);
        $title            = $this->formatTitle($activityRow);
        $description      = $this->formatDescription($activityRow);
        $participatingOrg = $this->formatParticipatingOrg($activityRow);
        $recipientCountry = $this->formatRecipientCountry($activityRow);
        $recipientRegion  = $this->formatRecipientRegion($activityRow);
        $sector           = $this->formatSector($activityRow);
        $activityDate     = $this->formatActivityDate($activityRow);

        $activity['identifier']                 = $identifier;
        $activity['title']                      = $title;
        $activity['description']                = $description;
        $activity['activity_status']            = $activityRow['activity_status'];
        $activity['participating_organization'] = $participatingOrg;
        $activity['recipient_country']          = $recipientCountry;
        $activity['recipient_region']           = $recipientRegion;
        $activity['sector']                     = $sector;
        $activity['activity_date']              = $activityDate;

        return $activity;
    }

    /**
     * prepare activity identifier array
     * @param $activityRow
     * @param $orgId
     * @return array
     */
    protected function formatIdentifier($activityRow, $orgId)
    {
        $org                                = $this->orgRepo->getOrganization($orgId)->reporting_org;
        $reportingOrgIdentifier             = $org[0]['reporting_organization_identifier'];
        $identifier                         = $this->readCsv->getActivityHeaders('identifier');
        $identifier['activity_identifier']  = $activityRow['activity_identifier'];
        $identifier['iati_identifier_text'] = $reportingOrgIdentifier . '-' . $activityRow['activity_identifier'];

        return $identifier;
    }

    /**
     * prepare title array
     * @param $activityRow
     * @return array
     */
    protected function formatTitle($activityRow)
    {
        $title                 = $this->readCsv->getActivityHeaders('title');
        $title[0]['narrative'] = $activityRow['activity_title'];

        return $title;
    }

    /**
     * prepare description array
     * @param $activityRow
     * @return array
     */
    protected function formatDescription($activityRow)
    {
        $descriptionTemplate = $this->readCsv->getActivityHeaders('description')[0];
        $description         = [];
        if (!empty($activityRow['description_general'])) {
            $descriptionTemplate['type']                      = 1;
            $descriptionTemplate['narrative'][0]['narrative'] = $activityRow['description_general'];
            $description[]                                    = $descriptionTemplate;
        }
        if (!empty($activityRow['description_objectives'])) {
            $descriptionTemplate['type']                      = 2;
            $descriptionTemplate['narrative'][0]['narrative'] = $activityRow['description_objectives'];
            $description[]                                    = $descriptionTemplate;
        }
        if (!empty($activityRow['description_target_group'])) {
            $descriptionTemplate['type']                      = 3;
            $descriptionTemplate['narrative'][0]['narrative'] = $activityRow['description_target_group'];
            $description[]                                    = $descriptionTemplate;
        }
        if (!empty($activityRow['description_other'])) {
            $descriptionTemplate['type']                      = 4;
            $descriptionTemplate['narrative'][0]['narrative'] = $activityRow['description_other'];
            $description[]                                    = $descriptionTemplate;
        }

        return $description;
    }

    /**
     * prepare participating organization array
     * @param $activityRow
     * @return array
     */
    protected function formatParticipatingOrg($activityRow)
    {
        $participatingOrgTemplate = $this->readCsv->getActivityHeaders('participatingOrganization')[0];
        $participatingOrg         = [];
        if (!empty($activityRow['funding_participating_organization'])) {
            $fundingOrgs = explode(';', $activityRow['funding_participating_organization']);
            foreach ($fundingOrgs as $fundingOrg) {
                $participatingOrgTemplate['organization_role']         = 1;
                $participatingOrgTemplate['narrative'][0]['narrative'] = $fundingOrg;
                $participatingOrg[]                                    = $participatingOrgTemplate;
            }
        }
        if (!empty($activityRow['implementing_participating_organization'])) {
            $implementingOrgs = explode(';', $activityRow['implementing_participating_organization']);
            foreach ($implementingOrgs as $implementingOrg) {
                $participatingOrgTemplate['organization_role']         = 4;
                $participatingOrgTemplate['narrative'][0]['narrative'] = $implementingOrg;
                $participatingOrg[]                                    = $participatingOrgTemplate;
            }
        }

        return $participatingOrg;
    }

    /**
     * prepare recipient country array
     * @param $activityRow
     * @return array
     */
    protected function formatRecipientCountry($activityRow)
    {
        $recipientCountryTemplate = $this->readCsv->getActivityHeaders('recipientCountry')[0];
        $countries                = explode(';', $activityRow['recipient_country']);
        $recipientCountry         = [];
        foreach ($countries as $country) {
            $recipientCountryTemplate['country_code'] = $country;
            $recipientCountry[]                       = $recipientCountryTemplate;
        }

        return $recipientCountry;
    }

    /**
     * prepare recipient region array
     * @param $activityRow
     * @return array
     */
    protected function formatRecipientRegion($activityRow)
    {
        $recipientRegionTemplate = $this->readCsv->getActivityHeaders('recipientRegion')[0];
        $regions                 = explode(';', $activityRow['recipient_region']);
        $recipientRegion         = [];
        foreach ($regions as $region) {
            $recipientRegionTemplate['region_code'] = $region;
            $recipientRegion[]                      = $recipientRegionTemplate;
        }

        return $recipientRegion;
    }

    /**
     * prepare sector array
     * @param $activityRow
     * @return array
     */
    protected function formatSector($activityRow)
    {
        $sectorTemplate = $this->readCsv->getActivityHeaders('sector')[0];
        $sectors        = explode(';', $activityRow['sector_dac_3digit']);
        $sector         = [];
        foreach ($sectors as $sectorRow) {
            $sectorTemplate['sector_vocabulary']    = 2;
            $sectorTemplate['sector_category_code'] = $sectorRow;
            $sector[]                               = $sectorTemplate;
        }

        return $sector;
    }

    /**
     * prepare activity date array
     * @param $activityRow
     * @return array
     */
    protected function formatActivityDate($activityRow)
    {
        $activityDateTemplate = $this->readCsv->getActivityHeaders('activityDate')[0];
        $activityDate         = [];
        if (!empty($activityRow['actual_start_date'])) {
            $activityDateTemplate['type'] = 2;
            $activityDateTemplate['date'] = $activityRow['actual_start_date'];
            $activityDate[]               = $activityDateTemplate;
        }
        if (!empty($activityRow['actual_end_date'])) {
            $activityDateTemplate['type'] = 4;
            $activityDateTemplate['date'] = $activityRow['actual_end_date'];
            $activityDate[]               = $activityDateTemplate;
        }
        if (!empty($activityRow['planned_start_date'])) {
            $activityDateTemplate['type'] = 1;
            $activityDateTemplate['date'] = $activityRow['planned_start_date'];
            $activityDate[]               = $activityDateTemplate;
        }
        if (!empty($activityRow['planned_end_date'])) {
            $activityDateTemplate['type'] = 3;
            $activityDateTemplate['date'] = $activityRow['planned_end_date'];
            $activityDate[]               = $activityDateTemplate;
        }

        return $activityDate;
    }

    /**
     * get the identifiers of all activity
     * @param $orgId
     * @return array
     */
    public function getIdentifiers($orgId)
    {
        $activities  = $this->activity->where('organization_id', $orgId)->get();
        $identifiers = [];

        foreach ($activities as $activityRow) {
            $identifiers[$activityRow->identifier['activity_identifier']] = $activityRow->id;
        }

        return $identifiers;
    }
}
