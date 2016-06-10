<?php namespace App\Core\V201\Parser;

use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Models\Settings;

/**
 * Class ActivityDataParser
 * @package App\Core\V201\Parser
 */
class ActivityDataParser
{
    /**
     * @var array
     */
    protected $activityData = [];
    /**
     * @var array
     */
    protected $activity;
    /**
     * @var integer
     */
    protected $orgId;
    /**
     * @var Activity
     */
    protected $activityModel;
    /**
     * @var Organization
     */
    protected $organizationModel;
    /**
     * @var Settings
     */
    protected $settingsModel;

    public function __construct(Activity $activityModel, Organization $organizationModel, Settings $settingsModel)
    {

        $this->activityModel     = $activityModel;
        $this->organizationModel = $organizationModel;
        $this->settingsModel     = $settingsModel;
    }

    /**
     * initializes with activity data
     * @param array $activity
     * @return ActivityDataParser
     */
    public function init(array $activity)
    {
        $this->activity                        = $activity;
        $this->orgId                           = session('org_id');
        $this->activityData['organization_id'] = $this->orgId;
        $this->setDefaultFieldValues();

        return $this;
    }

    /**
     * @return array
     */
    public function getActivityData()
    {
        return $this->activityData;
    }

    /**
     * create activity
     */
    public function save()
    {
        return $this->activityModel->create($this->activityData);
    }

    /**
     * return specific element data template
     * @param $templateName
     * @return array
     */
    public function getJsonTemplate($templateName)
    {
        $content = file_get_contents(app_path(sprintf('/Core/V201/Template/Activity/%s.json', $templateName)));

        return json_decode($content, true);
    }

    /**
     * set activity default field values
     */
    protected function setDefaultFieldValues()
    {
        $defaultFieldValues                         = $this->settingsModel->where('organization_id', $this->orgId)->first()->default_field_values;
        $this->activityData['default_field_values'] = $defaultFieldValues;
        $this->activityData['collaboration_type']   = $defaultFieldValues[0]['default_collaboration_type'];
        $this->activityData['default_flow_type']    = $defaultFieldValues[0]['default_flow_type'];
        $this->activityData['default_finance_type'] = $defaultFieldValues[0]['default_finance_type'];
        $this->activityData['default_aid_type']     = $defaultFieldValues[0]['default_aid_type'];
        $this->activityData['default_tied_status']  = $defaultFieldValues[0]['default_tied_status'];
    }

    /**
     * set activity identifier
     */
    public function setIdentifier()
    {
        $reportingOrg                     = $this->organizationModel->find($this->orgId)->reporting_org;
        $reportingOrgIdentifier           = $reportingOrg[0]['reporting_organization_identifier'];
        $activityIdentifier               = $this->activity['activity_identifier'];
        $this->activityData['identifier'] = ["activity_identifier" => $activityIdentifier, 'iati_identifier_text' => sprintf('%s-%s', $reportingOrgIdentifier, $activityIdentifier)];
    }

    /**
     * set activity title
     */
    public function setTitle()
    {
        $this->activityData['title'] = [["language" => "", "narrative" => $this->activity['activity_title']]];
    }

    /**
     * set activity descriptions
     */
    public function setDescription()
    {
        $description         = [];
        $descriptionTemplate = $this->getJsonTemplate('description')[0];

        if ($value = $this->activity['description_general']) {
            $descriptionTemplate['type']                      = '1';
            $descriptionTemplate['narrative'][0]['narrative'] = $value;
            $description[]                                    = $descriptionTemplate;
        }
        if ($value = $this->activity['description_objectives']) {
            $descriptionTemplate['type']                      = '2';
            $descriptionTemplate['narrative'][0]['narrative'] = $value;
            $description[]                                    = $descriptionTemplate;
        }
        if ($value = $this->activity['description_target_group']) {
            $descriptionTemplate['type']                      = '3';
            $descriptionTemplate['narrative'][0]['narrative'] = $value;
            $description[]                                    = $descriptionTemplate;
        }
        if ($value = $this->activity['description_other']) {
            $descriptionTemplate['type']                      = '4';
            $descriptionTemplate['narrative'][0]['narrative'] = $value;
            $description[]                                    = $descriptionTemplate;
        }

        $this->activityData['description'] = $description;
    }

    /**
     * set activity status
     */
    public function setStatus()
    {
        $this->activityData['activity_status'] = $this->activity['activity_status'];
    }

    /**
     * set activity dates
     */
    public function setDate()
    {
        $date         = [];
        $dateTemplate = $this->getJsonTemplate('activityDate')[0];

        if ($value = $this->activity['planned_start_date']) {
            $dateTemplate['date'] = formatDate($value, 'Y-m-d');
            $dateTemplate['type'] = 1;
            $date[]               = $dateTemplate;
        }
        if ($value = $this->activity['actual_start_date']) {
            $dateTemplate['date'] = formatDate($value, 'Y-m-d');
            $dateTemplate['type'] = 2;
            $date[]               = $dateTemplate;
        }
        if ($value = $this->activity['planned_end_date']) {
            $dateTemplate['date'] = formatDate($value, 'Y-m-d');
            $dateTemplate['type'] = 3;
            $date[]               = $dateTemplate;
        }
        if ($value = $this->activity['actual_end_date']) {
            $dateTemplate['date'] = formatDate($value, 'Y-m-d');
            $dateTemplate['type'] = 4;
            $date[]               = $dateTemplate;
        }

        $this->activityData['activity_date'] = $date;
    }

    /**
     * set activity participating organizations
     */
    public function setParticipatingOrg()
    {
        $participatingOrg         = [];
        $participatingOrgTemplate = $this->getJsonTemplate('participatingOrganization')[0];

        if ($values = $this->activity['funding_participating_organization']) {
            $values = explode(';', $values);
            foreach ($values as $value) {
                $participatingOrgTemplate['organization_role']         = 1;
                $participatingOrgTemplate['narrative'][0]['narrative'] = $value;
                $participatingOrg[]                                    = $participatingOrgTemplate;
            }

        }
        if ($values = $this->activity['implementing_participating_organization']) {
            $values = explode(';', $values);
            foreach ($values as $value) {
                $participatingOrgTemplate['organization_role']         = 4;
                $participatingOrgTemplate['narrative'][0]['narrative'] = $value;
                $participatingOrg[]                                    = $participatingOrgTemplate;
            }
        }

        $this->activityData['participating_organization'] = $participatingOrg;
    }

    /**
     * set activity recipient country
     */
    public function setRecipientCountry()
    {
        $recipientCountry         = [];
        $recipientCountryTemplate = $this->getJsonTemplate('recipientCountry')[0];

        if ($values = $this->activity['recipient_country']) {
            $values = explode(';', $values);
            foreach ($values as $value) {
                $recipientCountryTemplate['country_code'] = $value;
                $recipientCountry[]                       = $recipientCountryTemplate;
            }
        }

        $this->activityData['recipient_country'] = $recipientCountry;
    }

    /**
     * set activity recipient region
     */
    public function setRecipientRegion()
    {
        $recipientRegion         = [];
        $recipientRegionTemplate = $this->getJsonTemplate('recipientRegion')[0];

        if ($values = $this->activity['recipient_region']) {
            $values = explode(';', $values);
            foreach ($values as $value) {
                $recipientRegionTemplate['region_code'] = $value;
                $recipientRegion[]                      = $recipientRegionTemplate;
            }
        }

        $this->activityData['recipient_region'] = $recipientRegion;
    }

    /**
     * set activity sector
     */
    public function setSector()
    {
        $sector         = [];
        $sectorTemplate = $this->getJsonTemplate('sector')[0];

        if ($values = $this->activity['sector_dac_5digit']) {
            $values = explode(';', $values);
            foreach ($values as $value) {
                $sectorTemplate['sector_code'] = $value;
                $sector[]                      = $sectorTemplate;
            }
        }

        $this->activityData['sector'] = $sector;
    }

    /**
     * set activity scope
     */
    public function setScope()
    {
        $this->activityData['activity_scope'] = $this->activity['activity_scope'];
    }
}
