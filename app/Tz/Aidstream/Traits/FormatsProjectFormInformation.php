<?php namespace App\Tz\Aidstream\Traits;

use App\Tz\Aidstream\Models\Organization;


/**
 * Class FormatsProjectFormInformation
 * @package App\Tz\Aidstream\Traits
 */
trait FormatsProjectFormInformation
{
    protected $mappings = [
        'identifier',
        'other_identifier',
        'title',
        'description',
        'activity_status',
        'activity_date',
        'participating_organization',
        'sector',
        'recipient_country',
        'location',
        'recipient_region'
    ];

    /**
     * @var array
     */
    protected $identifier = ['activity_identifier' => '', 'iati_identifier_text' => ''];

    /**
     * @var array
     */
    protected $otherIdentifier = [
        [
            'reference' => '',
            'type'      => '',
            'owner_org' => [['reference' => '', 'narrative' => [['narrative' => '', 'language' => '']]]]
        ]
    ];

    /**
     * @var array
     */
    protected $title = [['narrative' => '', 'language' => '']];

    /**
     * @var array
     */
    protected $description = [
        [
            'type'      => '',
            'narrative' => [
                [
                    'narrative' => '',
                    'language'  => ''
                ]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $activityStatus = 0;

    /**
     * @var array
     */
    protected $activityDate = [
        [
            "date"      => "",
            "type"      => "",
            "narrative" => [
                [["narrative" => "", "language" => ""]]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $participatingOrganization = [
        [
            "organization_role" => "",
            "identifier"        => "",
            "organization_type" => "",
            "narrative"         => [["narrative" => "", "language" => ""]]
        ]
    ];

    /**
     * @var array
     */
    protected $sector = [
        "sector_vocabulary"    => "",
        "sector_code"          => "",
        "sector_category_code" => "",
        "sector_text"          => "",
        "percentage"           => "",
        "narrative"            => [["narrative" => "", "language" => ""]],
        "vocabulary_uri"       => ""
    ];

    /**
     * @var array
     */
    protected $recipientCountry = [["country_code" => "", "percentage" => "", "narrative" => [["narrative" => "", "language" => ""]]]];

    /**
     * @var array
     */
    protected $location = [
        [
            "reference"            => "",
            "location_reach"       => [["code" => ""]],
            "location_id"          => [["vocabulary" => "", "code" => ""]],
            "name"                 => [["narrative" => []]],
            "location_description" => [["narrative" => []]],
            "activity_description" => [["narrative" => []]],
            "administrative"       => [["vocabulary" => "", "code" => "", "level" => ""]],
            "point"                => [["srs_name" => "", "position" => [["latitude" => "", "longitude" => ""]]]],
            "exactness"            => [["code" => ""]],
            "location_class"       => [["code" => ""]],
            "feature_designation"  => [["code" => ""]]
        ]
    ];

    /**
     * @var array
     */
    protected $recipientRegion = [["region_code" => "", "region_vocabulary" => "", "percentage" => "", "narrative" => [["narrative" => "", "language" => ""]]]];

    /**
     * Process form details.
     * @param $projectDetails
     * @return array
     */
    public function process($projectDetails)
    {
        $mappings = [];
        $settings = app()->make(Organization::class)->query()->where('id', '=', session('org_id'))->first()->settings;

        foreach ($this->mappings as $mapping) {
            $map                = camel_case($mapping);
            $mappings[$mapping] = $this->$map;
        }

        $details                    = $this->fill($mappings, $projectDetails);
        $details['organization_id'] = $projectDetails['organization_id'];

        $details['default_field_values'] = [
            [
                'default_currency'  => getVal($settings->default_field_values, [0, 'default_currency']),
                'default_language'  => getVal($settings->default_field_values, [0, 'default_language']),
                'default_hierarchy' => 1
            ]
        ];

        $details['location'][0]['administrative'] = $projectDetails['location'];

        foreach ($projectDetails['location'] as $key => $location) {
            $details['location'][$key]                   = $this->location[0];
            $details['location'][$key]['administrative'] = $location['administrative'];
        }

        return $details;
    }

    /**
     * Fill form values according to the IATI mappings.
     * @param array $mappings
     * @param       $projectDetails
     * @return array
     */
    protected function fill(array $mappings, $projectDetails)
    {
        foreach ($mappings as $key => $value) {
            $map = camel_case($key);

            if (method_exists($this, $map)) {
                $mappings[$key] = $this->$map($projectDetails);
            }
        }

        return $mappings;
    }

    /**
     * Map Identifier.
     * @param $projectDetails
     * @return array
     */
    protected function identifier($projectDetails)
    {
        $organization   = app()->make(Organization::class)->find(session('org_id'));
        $iatiIdentifier = sprintf('%s-%s', $organization->reporting_org[0]['reporting_organization_identifier'], $projectDetails['identifier']);

        return [
            'activity_identifier'  => $projectDetails['identifier'],
            'iati_identifier_text' => $iatiIdentifier
        ];
    }

    /**
     * Map Title.
     * @param $projectDetails
     * @return array
     */
    protected function title($projectDetails)
    {
        return [
            [
                'narrative' => $projectDetails['title'],
                'language'  => ''
            ]
        ];
    }

    /**
     * Map Description.
     * @param $projectDetails
     * @return array
     */
    protected function description($projectDetails)
    {
        $details = [
            [
                'type'      => 1,
                'narrative' => [['narrative' => $projectDetails['description'], 'language' => '']]
            ]
        ];

        if ($projectDetails['objectives']) {
            $details[] = [
                'type'      => 2,
                'narrative' => [['narrative' => $projectDetails['objectives'], 'language' => '']]
            ];
        }

        if ($projectDetails['target_groups']) {
            $details[] = [
                'type'      => 3,
                'narrative' => [['narrative' => $projectDetails['target_groups'], 'language' => '']]
            ];
        }

        return $details;
    }

    /**
     * Map ActivityStatus.
     * @param $projectDetails
     * @return mixed
     */
    protected function activityStatus($projectDetails)
    {
        return $projectDetails['activity_status'];
    }

    /**
     * Map Sector.
     * @param $projectDetails
     * @return array
     */
    protected function sector($projectDetails)
    {
        return [
            [
                "sector_vocabulary"    => 2,
                "sector_code"          => '',
                "sector_category_code" => $projectDetails['sector'],
                "sector_text"          => "",
                "percentage"           => "",
                "narrative"            => [["narrative" => "", "language" => ""]],
                "vocabulary_uri"       => ""
            ]
        ];
    }

    /**
     * Map Recipient Country.
     * @param $projectDetails
     * @return array
     */
    protected function recipientCountry($projectDetails)
    {
        return [
            [
                "country_code" => $projectDetails['recipient_country'],
                "percentage"   => "",
                "narrative"    => [["narrative" => "", "language" => ""]]
            ]
        ];
    }

//    /**
//     * Map Recipient Region.
//     * @param $projectDetails
//     * @return array
//     */
//    protected function recipientRegion($projectDetails)
//    {
//        return [
//            [
//                "region_code"       => $projectDetails['recipient_region'],
//                "region_vocabulary" => "",
//                "percentage"        => "",
//                "narrative"         => [["narrative" => "", "language" => ""]]
//            ]
//        ];
//    }

    /**
     * Map Activity Date.
     * @param $projectDetails
     * @return array
     */
    protected function activityDate($projectDetails)
    {
        $details = [
            [
                "date"      => $projectDetails['start_date'],
                "type"      => 2,
                "narrative" => [
                    [["narrative" => "", "language" => ""]]
                ]
            ]
        ];

        if ($projectDetails['end_date']) {
            $details[] = [
                "date"      => $projectDetails['end_date'],
                "type"      => 4,
                "narrative" => [
                    [["narrative" => "", "language" => ""]]
                ]
            ];
        }

        return $details;
    }

    /**
     * Map Participating Organization.
     * @param $projectDetails
     * @return array
     */
    protected function participatingOrganization($projectDetails)
    {
        $details = [];

        foreach ($projectDetails['funding_organization'] as $fundingOrganization) {
            $details[] = [
                "organization_role" => 1,
                "identifier"        => "",
                "organization_type" => $fundingOrganization['funding_organization_type'],
                "narrative"         => [["narrative" => $fundingOrganization['funding_organization_name'], "language" => ""]]
            ];
        }

        foreach ($projectDetails['implementing_organization'] as $implementingOrganization) {
            $details[] = [
                "organization_role" => 4,
                "identifier"        => "",
                "organization_type" => $implementingOrganization['implementing_organization_type'],
                "narrative"         => [["narrative" => $implementingOrganization['implementing_organization_name'], "language" => ""]]
            ];
        }

        return $details;
    }

    /**
     * Process Default Field Values.
     * @param $projectDetails
     * @return array
     */
    public function processDefaultFieldValues($projectDetails)
    {
        return [
            'default_field_values' => [
                [
                    'default_currency'  => $projectDetails['default_currency'],
                    'default_language'  => $projectDetails['default_language'],
                    'default_hierarchy' => 1
                ]
            ]
        ];
    }

    /**
     * Map data in reverse order for views.
     * @param $projectDetails
     * @return array|mixed
     */
    public function reverseMap($projectDetails)
    {
        $details['identifier']        = $projectDetails->identifier ? getVal($projectDetails->identifier, ['activity_identifier']) : '';
        $details['title']             = $projectDetails->title ? getVal($projectDetails->title, [0, 'narrative']) : '';
        $details                      = $this->mapDescription($projectDetails, $details);
        $details['sector']            = $projectDetails->sector ? getVal($projectDetails->sector, [0, 'sector_category_code']) : '';
        $details                      = $this->mapProjectDate($projectDetails, $details);
        $details['recipient_country'] = $projectDetails->recipient_country ? getVal($projectDetails->recipient_country, [0, 'country_code']) : '';
        $details['activity_status']   = $projectDetails->activity_status;
        $details['id']                = $projectDetails->id;
        $details                      = $this->mapParticipatingOrganization($projectDetails, $details);
        $details['location']          = $projectDetails['location'];

        return $details;
    }

    /**
     * Reformat Description.
     * @param $projectDetails
     * @param $details
     * @return mixed
     */
    protected function mapDescription($projectDetails, array $details)
    {
        foreach ($projectDetails->description as $description) {
            if (getVal($description, ['type']) == 1) {
                $details['description'] = getVal($description, ['narrative', 0, 'narrative']);
            }

            if (getVal($description, ['type']) == 2) {
                $details['objectives'] = getVal($description, ['narrative', 0, 'narrative']);
            }

            if (getVal($description, ['type']) == 3) {
                $details['target_groups'] = getVal($description, ['narrative', 0, 'narrative']);
            }
        }

        return $details;
    }

    /**
     * @param       $projectDetails
     * @param array $details
     * @return array
     */
    protected function mapProjectDate($projectDetails, array $details)
    {
        foreach ($projectDetails->activity_date as $activityDate) {
            if (getVal($activityDate, ['type']) == 2) {
                $details['start_date'] = getVal($activityDate, ['date']);
            }

            if (getVal($activityDate, ['type']) == 4) {
                $details['end_date'] = getVal($activityDate, ['date']);
            }
        }

        return $details;
    }

    /**
     * Map Participating Organization.
     * @param       $projectDetails
     * @param array $details
     * @return array
     */
    protected function mapParticipatingOrganization($projectDetails, array $details)
    {
        $participatingOrganizationDetails = [];

        foreach ($projectDetails->participating_organization as $participatingOrganization) {
            if (getVal($participatingOrganization, ['organization_role']) == 1) {
                $participatingOrganizationDetails['funding_organization'][] = [
                    'funding_organization_name' => getVal($participatingOrganization, ['narrative', 0, 'narrative']),
                    'funding_organization_type' => getVal($participatingOrganization, ['organization_type'])
                ];
            }

            if (getVal($participatingOrganization, ['organization_role']) == 4) {
                $participatingOrganizationDetails['implementing_organization'][] = [
                    'implementing_organization_name' => getVal($participatingOrganization, ['narrative', 0, 'narrative']),
                    'implementing_organization_type' => getVal($participatingOrganization, ['organization_type'])
                ];
            }
        }
        $details['participating_organization'] = $participatingOrganizationDetails;

        return $details;
    }
}
