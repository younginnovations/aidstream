<?php namespace App\Lite\Services\Data\V202\Activity;

use App\Lite\Services\Data\Contract\MapperInterface;
use App\Lite\Services\Traits\GeocodeReverser;
use App\Models\Organization\Organization;

/**
 * Class ActivityData
 * @package App\Lite\Services\Data\Activity
 */
class Activity implements MapperInterface
{
    /**
     * Code for funding organisation role
     */
    const FUNDING_ORGANIZATION_ROLE = 1;

    /**
     * Code for implementing organisation role
     */
    const IMPLEMENTING_ORGANIZATION_ROLE = 4;

    /**
     * Code for start date (Actual)
     */
    const START_DATE = 2;

    /**
     * Code for end date (Actual)
     */
    const END_DATE = 4;

    /**
     * Code for target groups type of description.
     */
    const TARGET_GROUPS = 3;

    /**
     * Code for objectives type of description.
     */
    const OBJECTIVES = 2;

    /**
     * Code for general type of description.
     */
    const GENERAL_DESCRIPTION = 1;

    /**
     *
     */
    const LOCATION_SRS_NAME_VALUE = 'http://www.opengis.net/def/crs/EPSG/0/4326';

    /**
     * Code for budget type
     */
    const BUDGET_TYPE = 1;

    /**
     * Code for budget status
     */
    const BUDGET_STATUS = 2;

    /**
     *
     */
    const DEFAULT_RECIPIENT_COUNTRY_PERCENTAGE = 100;

    /**
     * Raw data holder for Activity entity.
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * Contains mapped data.
     * @var array
     */
    protected $mappedData = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * Data template for Activity.
     *
     * @var array
     */
    protected $template = [];

    /**
     * Path to template.
     */
    const BASE_TEMPLATE_PATH = 'Services/XmlImporter/Foundation/Support/Templates/V202.json';

    /**
     *
     */
    const LOCATION_ADMINISTRATIVE_VOCABULARY = 'G1';
    /**
     *
     */
    const LOCATION_DISTRICT_LEVEL = '2';
    /**
     *
     */
    const LOCATION_REGION_LEVEL = '1';

    /**
     * @var array
     */
    protected $mappedFields = [
        'activity_identifier'        => 'identifier',
        'activity_title'             => 'title',
        'activity_title_np'          => 'title_np',
        'general_description'        => 'description',
        'objectives'                 => 'description',
        'target_groups'              => 'description',
        'activity_status'            => 'activity_status',
        'sector'                     => 'sector',
        'start_date'                 => 'activity_date',
        'end_date'                   => 'activity_date',
        'country'                    => 'recipient_country',
        'funding_organisations'      => 'participating_organization',
        'implementing_organisations' => 'participating_organization',
        'location'                   => 'location'
    ];

    /**
     * ActivityData constructor.
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        $this->rawData  = $rawData;
        $this->template = $this->loadTemplate();
    }

    /**
     * Map the raw data to element template.
     *
     * @return array
     */
    public function map()
    {
        foreach ($this->rawData as $key => $value) {
            if (!empty($value) && array_key_exists($key, $this->mappedFields)) {
                $methodName = $this->mappedFields[$key];

                if (method_exists($this, $methodName)) {
                    $this->resetIndex($this->mappedFields[$key]);
                    $this->{$this->mappedFields[$key]}($key, $value, $this->getTemplateOf($key));
                }
            }
        }

        return $this->mappedData;
    }

    /**
     * Map database data into frontend compatible format.
     *
     * @return mixed
     */
    public function reverseMap()
    {
        $this->mappedData['activity_identifier'] = getVal($this->rawData, ['identifier', 'activity_identifier']);
        $this->mappedData['activity_title']      = getVal($this->rawData, ['title', 0, 'narrative']);
        $this->mappedData['activity_title_in_np'] = getVal($this->rawData, ['title',0,'language']);
        $this->mappedData['activity_status']     = getVal($this->rawData, ['activity_status']);

        foreach (getVal($this->rawData, ['sector'], []) as $index => $value) {
            $this->mappedData['sector'][$index] = getVal($value, ['sector_code']);
        }

        $this->reverseMapDescription()
             ->reverseMapActivityDate()
             ->reverseMapParticipatingOrganisation()
             ->reverseMapRecipientCountry()
             ->reverseMapLocation();

        return $this->mappedData;
    }

    /**
     * Reset the index value to 0.
     *
     * @param $key
     */
    protected function resetIndex($key)
    {
        if (!array_key_exists($key, $this->mappedData)) {
            $this->index = 0;
        }
    }

    /**
     * Map the data to identifier template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function identifier($key, $value, $template)
    {
        $organization             = Organization::findOrFail(session('org_id'));
        $reporting_org_identifier = getVal($organization->reporting_org, [0, 'reporting_organization_identifier'], '');

        $template[$key]                              = $value;
        $template['iati_identifier_text']            = sprintf('%s-%s', $reporting_org_identifier, $value);
        $this->mappedData[$this->mappedFields[$key]] = $template;
    }

    /**
     * Map the data to title template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function title($key, $value, $template)
    {
        $template['narrative'] = $value;
        $template['language'] = '';

        $this->mappedData[$this->mappedFields[$key]][$this->index] = $template;
    }

    /**
     * Map the data to description template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function description($key, $value, $template)
    {
        $descriptionType = $this->getDescriptionType($key);

        if ($descriptionType) {
            $this->mappedData['description'][$this->index]              = $template['type'];
            $this->mappedData['description'][$this->index]['type']      = $descriptionType;
            $this->mappedData['description'][$this->index]['narrative'] = [['narrative' => $value, 'language' => '']];
            $this->index ++;
        }
    }

    /**
     * Map the data to activity status template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function activity_status($key, $value, $template)
    {
        $this->mappedData[$key] = $value;
    }

    /**
     * Map the data to sector template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function sector($key, $value, $template)
    {
        foreach ($value as $index => $val) {
            $template['sector_vocabulary']      = '1';
            $template['sector_code']            = $val;
            $this->mappedData['sector'][$index] = $template;
        }
    }

    /**
     * Map the data to activity date template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function activity_date($key, $value, $template)
    {
        $activityDateType = $this->getActivityDateType($key);

        if ($activityDateType) {
            $this->mappedData['activity_date'][$this->index]         = $template;
            $this->mappedData['activity_date'][$this->index]['type'] = $activityDateType;
            $this->mappedData['activity_date'][$this->index]['date'] = $value;
            $this->index ++;
        }
    }

    /**
     *  Map the data to recipient country template.
     *
     * @param $key
     * @param $value
     * @param $template
     * @param $index
     * @param $percentage
     */
    protected function recipient_country($key, $value, $template, $index, $percentage)
    {
        $this->mappedData['recipient_country'][$index]                 = $template;
        $this->mappedData['recipient_country'][$index]['country_code'] = $value;
        $this->mappedData['recipient_country'][$index]['percentage']   = getVal($percentage, [$index]);
    }

    /**
     * Map the data to participating organisation template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function participating_organization($key, $value, $template)
    {
        $organizationRole = $this->getOrganizationRole($key);
        foreach ($value as $index => $field) {
            if ($organizationRole) {
                $organizationType = getVal($field, ['organisation_type'], '');
                $organizationName = getVal($field, ['organisation_name'], '');

                if ($organizationName != "" || $organizationType != "") {
                    $this->mappedData['participating_organization'][$this->index]                              = $template;
                    $this->mappedData['participating_organization'][$this->index]['organization_role']         = $organizationRole;
                    $this->mappedData['participating_organization'][$this->index]['organization_type']         = $organizationType;
                    $this->mappedData['participating_organization'][$this->index]['narrative'][0]['narrative'] = $organizationName;
                    $this->index ++;
                }
            }
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $template
     */
    protected function location($key, $value, $template)
    {
        $administrativeTemplate = getVal($template, ['administrative'], []);
        $percentage             = $this->calculatePercentageForCountry(count($value));
        $index                  = 0;

        foreach ($value as $location) {
            $countryCode = getVal($location, ['country'], '');
            $this->recipient_country('recipient_country', $countryCode, $this->getTemplateOf('country'), $index, $percentage);

            if (empty(getVal($location, ['administrative'], []))) {
                $this->mappedData[$key][$this->index]                 = $template;
                $this->mappedData[$key][$this->index]['country_code'] = $countryCode;
                $this->index ++;
            }


            foreach (getVal($location, ['administrative'], []) as $administrativeIndex => $administrative) {
                $this->mappedData[$key][$this->index] = $template;
                $region                               = getVal($administrative, ['region']);
                $district                             = getVal($administrative, ['district']);
                foreach (getVal($administrative, ['point'], []) as $pointIndex => $point) {
                    $latitude     = getVal($point, ['latitude']);
                    $longitude    = getVal($point, ['longitude']);
                    $locationName = getVal($point, ['locationName']);
                }

                $this->mappedData[$key][$this->index]['country_code'] = getVal($location, ['country']);
                if ($latitude != "" || $latitude != "") {
                    $this->mappedData[$key][$this->index]['name'][0]['narrative'][0]['narrative'] = $locationName;
                    $this->mappedData[$key][$this->index]['point'][0]['srs_name']                 = self::LOCATION_SRS_NAME_VALUE;
                    $this->mappedData[$key][$this->index]['point'][0]['position'][0]              = [
                        'latitude'  => $latitude,
                        'longitude' => $longitude
                    ];
                }

                if ($region != "" && $countryCode == "TZ") {
                    $this->mappedData[$key][$this->index]['administrative'] = $this->administrative($region, $district, $administrativeTemplate);
                }
                $this->index ++;
            }
            $index ++;
        }
    }

    /**
     * @param $region
     * @param $district
     * @param $template
     * @return mixed
     */
    protected function administrative($region, $district, $template)
    {
        $administrative = $template;

        $administrative[0]['vocabulary'] = self::LOCATION_ADMINISTRATIVE_VOCABULARY;
        $administrative[0]['code']       = $region;
        $administrative[0]['level']      = self::LOCATION_REGION_LEVEL;

        $administrative[1]['vocabulary'] = self::LOCATION_ADMINISTRATIVE_VOCABULARY;
        $administrative[1]['code']       = $district;
        $administrative[1]['level']      = self::LOCATION_DISTRICT_LEVEL;

        return $administrative;
    }

    /**
     * Reverse map description for form.
     *
     * @return $this
     */
    protected function reverseMapDescription()
    {
        foreach (getVal($this->rawData, ['description'], []) as $descriptions) {
            $descriptionType = $this->getDescriptionType(getVal($descriptions, ['type']), true);

            if ($descriptionType) {
                $description                        = getVal($descriptions, ['narrative', 0, 'narrative']);
                $this->mappedData[$descriptionType] = $description;
            }
        }

        return $this;
    }

    /**
     * Reverse map activity date for form.
     *
     * @return $this
     */
    protected function reverseMapActivityDate()
    {
        foreach (getVal($this->rawData, ['activity_date'], []) as $activityDate) {
            $activityDateType = $this->getActivityDateType(getVal($activityDate, ['type']), true);

            if ($activityDateType) {
                $date                                = getVal($activityDate, ['date']);
                $this->mappedData[$activityDateType] = $date;
            }
        }

        return $this;
    }

    /**
     * Reverse map recipient country for form.
     *
     * @return $this
     */
    protected function reverseMapRecipientCountry()
    {
        foreach (getVal($this->rawData, ['recipient_country'], []) as $index => $country) {
            $this->mappedData['country'][$index] = getVal($country, ['country_code']);
        }

        return $this;
    }

    /**
     * Reverse map participating organisations for form.
     *
     * @return $this
     */
    protected function reverseMapParticipatingOrganisation()
    {
        foreach (getVal($this->rawData, ['participating_organization'], []) as $index => $organization) {
            $organizationRole = $this->getOrganizationRole(getVal($organization, ['organization_role']), true);

            if ($organizationRole) {
                if (!array_key_exists($organizationRole, $this->mappedData)) {
                    $index = 0;
                }

                $organizationType                                                 = getVal($organization, ['organization_type']);
                $organizationName                                                 = getVal($organization, ['narrative', 0, 'narrative']);
                $this->mappedData[$organizationRole][$index]['organisation_name'] = $organizationName;
                $this->mappedData[$organizationRole][$index]['organisation_type'] = $organizationType;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function reverseMapLocation()
    {
        $countryArray        = [];
        $administrativeIndex = 0;

        foreach (getVal($this->rawData, ['location'], []) as $index => $location) {
            if (array_key_exists('country_code', $location)) {
                $countryCode   = getVal($location, ['country_code']);
                $locationIndex = $this->getLocationIndex($countryCode);

                if (in_array($countryCode, $countryArray)) {
                    $administrativeIndex ++;
                } else {
                    $administrativeIndex = 0;
                    $countryArray[]      = $countryCode;
                }

                $this->mappedData['location'][$locationIndex]['country'] = $countryCode;
                foreach (getVal($location, ['point'], []) as $pointIndex => $point) {
                    $longitude = getVal($point, ['position', 0, 'longitude']);
                    $latitude  = getVal($point, ['position', 0, 'latitude']);

                    if ($longitude != "" || $latitude != "") {
                        $this->mappedData['location'][$locationIndex]['administrative'][$administrativeIndex]['point'][0]['locationName'] = getVal($location, ['name', 0, 'narrative', 0, 'narrative']);
                        $this->mappedData['location'][$locationIndex]['administrative'][$administrativeIndex]['point'][0]['latitude']     = $latitude;
                        $this->mappedData['location'][$locationIndex]['administrative'][$administrativeIndex]['point'][0]['longitude']    = $longitude;
                    }
                }

                if (($region = getVal($location, ['administrative', 0, 'code'])) != "") {
                    $this->mappedData['location'][$locationIndex]['administrative'][$administrativeIndex]['region']   = getVal($location, ['administrative', 0, 'code']);
                    $this->mappedData['location'][$locationIndex]['administrative'][$administrativeIndex]['district'] = getVal($location, ['administrative', 1, 'code']);
                }
            }
        }

        return $this->mappedData;
    }

    /**
     * Returns index of the country.
     * @param $countryCode
     * @return int|string
     */
    protected function getLocationIndex($countryCode)
    {
        foreach (getVal($this->rawData, ['recipient_country'], []) as $locationIndex => $country) {
            if ($countryCode == getVal($country, ['country_code'])) {
                return $locationIndex;
            }
        }
    }

    /**
     * Returns the organisation role code.
     *
     * If reversed is true, then key is returned.
     *
     * @param      $key
     * @param bool $reversed
     * @return mixed|string
     */
    protected function getOrganizationRole($key, $reversed = false)
    {
        $organizationRole = [
            'funding_organisations'      => self::FUNDING_ORGANIZATION_ROLE,
            'implementing_organisations' => self::IMPLEMENTING_ORGANIZATION_ROLE
        ];

        if ($reversed) {
            return (array_key_exists($key, array_flip($organizationRole))) ? getVal(array_flip($organizationRole), [$key]) : false;
        }

        return (array_key_exists($key, $organizationRole)) ? $organizationRole[$key] : false;
    }

    /**
     * Returns the type of activity date code.
     * If reversed is true, then key is returned.
     *
     * @param      $key
     * @param bool $reversed
     * @return mixed|string
     */
    protected function getActivityDateType($key, $reversed = false)
    {
        $activityDates = [
            'start_date' => self::START_DATE,
            'end_date'   => self::END_DATE
        ];

        if ($reversed) {
            return array_key_exists($key, array_flip($activityDates)) ? getVal(array_flip($activityDates), [$key]) : false;
        }

        return array_key_exists($key, $activityDates) ? $activityDates[$key] : false;
    }

    /**
     * Returns the description type code.
     * If reversed is true, then key is returned.
     *
     * @param      $key
     * @param bool $reversed
     * @return mixed|string
     */
    protected function getDescriptionType($key, $reversed = false)
    {
        $description = [
            'general_description' => self::GENERAL_DESCRIPTION,
            'objectives'          => self::OBJECTIVES,
            'target_groups'       => self::TARGET_GROUPS
        ];

        if ($reversed) {
            return array_key_exists($key, array_flip($description)) ? getVal(array_flip($description), [$key]) : false;
        }

        return array_key_exists($key, $description) ? $description[$key] : false;
    }

    /**
     * Returns specific template of an element.
     *
     * @param $key
     * @return mixed
     */
    public function getTemplateOf($key)
    {
        return $this->template[$this->mappedFields[$key]];
    }

    /**
     * Returns template of the elements.
     *
     * @return mixed
     */
    public function loadTemplate()
    {
        return json_decode(file_get_contents(app_path(self::BASE_TEMPLATE_PATH)), true);
    }

    /**
     * Calculates the percentage for recipient country.
     *
     * @param $noOfCountry
     * @return array
     */
    protected function calculatePercentageForCountry($noOfCountry)
    {
        $percentage      = (100 % $noOfCountry == 0) ? (100 / $noOfCountry) : round(100 / $noOfCountry, 2);
        $percentageArray = [];

        for ($i = 0; $i < $noOfCountry; $i ++) {
            $percentageArray[] = $percentage;
        }
        $percentageSum = array_sum($percentageArray);

        if ($percentageSum > 100) {
            $diff               = $percentageSum - 100;
            $percentageArray[0] = $percentageArray[0] - $diff;
        }

        if ($percentageSum < 100) {
            $diff               = 100 - $percentageSum;
            $percentageArray[0] = $percentageArray[0] + $diff;
        }

        return $percentageArray;
    }
}
