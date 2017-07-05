<?php namespace Tests\App\xmlImporter;

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Activity;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlErrorServiceProvider;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;
use Sabre\Xml\Service;
use Test\AidStreamTestCase;

class V1ActivityDataMappingTest extends AidStreamTestCase
{
    protected $activity;
    protected $xmlServiceProvider;
    protected $service;
    protected $data;
    protected $template;
    protected $xmlErrorServiceProvider;

    public function setUp()
    {
        parent::setUp();
        $this->service                 = new Service();
        $this->xmlErrorServiceProvider = new XmlErrorServiceProvider();
        $this->xmlServiceProvider      = new XmlServiceProvider($this->service, $this->xmlErrorServiceProvider);
        $this->activity                = new Activity();
        $xml                           = file_get_contents('tests/app/xmlImporter/xml/V103.xml');
        $this->data                    = $this->xmlServiceProvider->load($xml);
        $this->template                = $template = json_decode(file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json')), true);
    }

    public function testReportingOrg()
    {
        $expectedIdentifierOrg      = 'GB-CHC-202918';
        $this->activity->identifier = [];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('reportingOrg');
        foreach ($data as $element) {
            $this->activity->reportingOrg($element, $template);
            $this->assertEquals($expectedIdentifierOrg, $this->activity->orgRef, "Testing reporting org");
        }
    }

    public function testIatiIdentifier()
    {
        $expectedIdentifier = [
            'activity_identifier'  => 'HECA91',
            'iati_identifier_text' => 'GB-CHC-202918-HECA91',
        ];

        $this->activity->orgRef = 'GB-CHC-202918';

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('iatiIdentifier');
        foreach ($data as $element) {
            $this->assertEquals($expectedIdentifier, $this->activity->iatiIdentifier($element, $template), "Testing Iati Identifier");
        }
    }

    public function testOtherIdentifier()
    {
        $expectedIdentifier = [
            0 => [
                'reference' => '105838-1',
                'type'      => '',
                'owner_org' => [
                    0 => [
                        'reference' => 'GB-1',
                        'narrative' => [
                            0 => [
                                'narrative' => 'DFID',
                                'language'  => ''
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $template        = $this->loadTemplate();
        $data            = $this->loadSpecificElementData('otherIdentifier');
        $otherIdentifier = [];
        foreach ($data as $element) {
            $otherIdentifier = $this->activity->otherIdentifier($element, $template);
        }
        $this->assertEquals($expectedIdentifier, $otherIdentifier, "Testing Other Identifier");
    }

    public function testTitle()
    {
        $expectedTitle = [
            0 => [
                'narrative' => 'Grant Performance Report',
                'language'  => ''
            ],
            1 => [
                'narrative' => 'Grant Informe sobre los resultados',
                'language'  => 'es'
            ],
            2 => [
                'narrative' => 'Rapport sur le rendement de subvention',
                'language'  => 'fr'
            ]
        ];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('title');
        $title    = [];
        foreach ($data as $element) {
            $title = $this->activity->title($element, $template);
        }
        $this->assertEquals($expectedTitle, $title, "Testing title");
    }

    public function testDescription()
    {
        $expectedDescription = [
            1 => [
                'type'      => '1',
                'narrative' => [
                    0 => [
                        'narrative' => 'Description text',
                        'language'  => 'en'
                    ],
                    1 => [
                        'narrative' => 'Descasdas dasasdription text Descasdas dasasdription textasdasd',
                        'language'  => ''
                    ]
                ]
            ],
            2 => [
                'type'      => '2',
                'narrative' => [
                    0 => [
                        'narrative' => 'Description text',
                        'language'  => 'en'
                    ]
                ]
            ]
        ];

        $template    = $this->loadTemplate();
        $data        = $this->loadSpecificElementData('description');
        $description = [];
        foreach ($data as $element) {
            $description = $this->activity->description($element, $template);
        }
        $this->assertEquals($expectedDescription, $description, "Testing Description");
    }

    public function testActivityDate()
    {
        $expectedDate = [
            0 => [
                'date'      => '2009-12-31',
                'type'      => '1',
                'narrative' => [
                    0 => [
                        'narrative' => '2006-04-01',
                        'language'  => ''
                    ]
                ]
            ],
            1 => [
                'date'      => '2009-12-31',
                'type'      => '2',
                'narrative' => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ],
            2 => [
                'date'      => '2009-12-31',
                'type'      => '3',
                'narrative' => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ],
            3 => [
                'date'      => '2009-12-31',
                'type'      => '4',
                'narrative' => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ];

        $template     = $this->loadTemplate();
        $data         = $this->loadSpecificElementData('activityDate');
        $activityDate = [];
        foreach ($data as $element) {
            $activityDate = $this->activity->activityDate($element, $template);
        }
        $this->assertEquals($expectedDate, $activityDate, "Testing Description");
    }

    public function testContactInfo()
    {
        $expectedContactInfo = [
            0 => [
                'type'            => '1',
                'organization'    => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Department for International Development',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'department'      => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'person_name'     => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'A Smith',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'job_title'       => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Database Manager',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Database Manager',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'telephone'       => [
                    0 => [
                        'telephone' => '+44 (0) 1355 84 3132'
                    ],
                    1 => [
                        'telephone' => '+44 (0) 1355 84 3132'
                    ]
                ],
                'email'           => [
                    0 => [
                        'email' => 'enquiry@dfid.gov.uk'
                    ]
                ],
                'website'         => [
                    0 => [
                        'website' => 'https://www.gov.uk/government/organisations/department-for-international-development'
                    ]
                ],
                'mailing_address' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Public Enquiry Point, Abercrombie House, Eaglesham Road, East Kilbride, Glasgow G75 8EA',
                                'language'  => ''
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $template    = $this->loadTemplate();
        $data        = $this->loadSpecificElementData('contactInfo');
        $contactInfo = [];
        foreach ($data as $element) {
            $contactInfo = $this->activity->contactInfo($element, $template);
        }

        $this->assertEquals($expectedContactInfo, $contactInfo, "Testing Description");
    }

    public function testParticipatingOrg()
    {
        $expectedParticipatingOrg = [
            0 => [
                'organization_role' => '1',
                'identifier'        => 'GB',
                'organization_type' => '10',
                'activity_id'       => '',
                'narrative'         => [
                    0 => [
                        'narrative' => 'UNITED KINGDOM',
                        'language'  => ''
                    ]
                ]
            ],
            1 => [
                'organization_role' => '3',
                'identifier'        => 'GB-1',
                'organization_type' => '10',
                'activity_id'       => '',
                'narrative'         => [
                    0 => [
                        'narrative' => 'Department for International Development',
                        'language'  => ''
                    ]
                ]
            ],
            2 => [
                'organization_role' => '4',
                'identifier'        => '22000',
                'organization_type' => '',
                'activity_id'       => '',
                'narrative'         => [
                    0 => [
                        'narrative' => 'Donor country-based NGO',
                        'language'  => 'en'
                    ]
                ]

            ]
        ];
        $template                 = $this->loadTemplate();
        $data                     = $this->loadSpecificElementData('participatingOrg');
        $participatingOrg         = [];

        foreach ($data as $element) {
            $participatingOrg = $this->activity->participatingOrg($element, $template);
        }
        $this->assertEquals($expectedParticipatingOrg, $participatingOrg);
    }

    public function testRecipientCountry()
    {
        $expectedRecipientCountry = [
            0 => [
                'country_code' => 'CG',
                'percentage'   => '',
                'narrative'    => [
                    0 => [
                        'narrative' => 'Republique Democratique du Congo',
                        'language'  => 'fr'
                    ]
                ]
            ],
            1 => [
                'country_code' => 'AO',
                'percentage'   => '40',
                'narrative'    => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ];
        $template                 = $this->loadTemplate();
        $data                     = $this->loadSpecificElementData('recipientCountry');
        $recipientCountry         = [];
        foreach ($data as $element) {
            $recipientCountry = $this->activity->recipientCountry($element, $template);
        }
        $this->assertEquals($expectedRecipientCountry, $recipientCountry);
    }

    public function testRecipientRegion()
    {
        $expectedRecipientRegion = [
            0 => [
                'region_code'       => '289',
                'region_vocabulary' => '',
                'vocabulary_uri'    => '',
                'percentage'        => '60',
                'narrative'         => [
                    0 => [
                        'narrative' => 'South of Sahara, regional',
                        'language'  => ''
                    ]
                ]
            ],
            1 => [
                'region_code'       => '189',
                'region_vocabulary' => '',
                'vocabulary_uri'    => '',
                'percentage'        => '40',
                'narrative'         => [
                    0 => [
                        'narrative' => 'North of Sahara, regional',
                        'language'  => ''
                    ]
                ]
            ]
        ];

        $template        = $this->loadTemplate();
        $data            = $this->loadSpecificElementData('recipientRegion');
        $recipientRegion = [];
        foreach ($data as $element) {
            $recipientRegion = $this->activity->recipientRegion($element, $template);
        }
        $this->assertEquals($expectedRecipientRegion, $recipientRegion);
    }

    public function testLocation()
    {
        $expectedLocation = [
            0 => [
                'reference'            => '',
                'location_reach'       => [0 => ['code' => '']],
                'location_id'          => [0 => ['vocabulary' => '', 'code' => '']],
                'name'                 => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Herat',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Côte d\'Ivoire',
                                'language'  => 'fr'
                            ]
                        ]
                    ]
                ],
                'location_description' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'This is location description',
                                'language'  => 'en'
                            ],
                            1 => [
                                'narrative' => 'This is location description',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'activity_description' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'administrative'       => [
                    0 => [
                        'vocabulary' => '',
                        'code'       => '',
                        'level'      => ''
                    ]
                ],
                'point'                => [
                    0 => [
                        'srs_name' => '',
                        'position' => [
                            0 => [
                                'latitude'  => '34.341944400000003000',
                                'longitude' => '62.203055599999971000'
                            ]
                        ]
                    ]
                ],
                'exactness'            => [
                    0 => ['code' => '2']
                ],
                'location_class'       => [
                    0 => ['code' => '']
                ],
                'feature_designation'  => [
                    0 => ['code' => '']
                ]
            ]
        ];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('location');
        $location = [];
        foreach ($data as $element) {
            $location = $this->activity->location($element, $template);
        }
        $this->assertEquals($expectedLocation, $location);
    }

    public function testSector()
    {
        $expectedSector = [
            0 => [
                'sector_vocabulary'    => '',
                'vocabulary_uri'       => '',
                'sector_code'          => '',
                'sector_category_code' => '',
                'sector_text'          => '13040',
                'percentage'           => '60',
                'narrative'            => [
                    0 => [
                        'narrative' => 'STD control including HIV/AIDS',
                        'language'  => 'en'
                    ]
                ]
            ],
            1 => [
                'sector_vocabulary'    => '1',
                'vocabulary_uri'       => '',
                'sector_code'          => 'BC',
                'sector_category_code' => '',
                'sector_text'          => '',
                'percentage'           => '',
                'narrative'            => [
                    0 => [
                        'narrative' => 'Central government administration',
                        'language'  => ''
                    ]
                ]
            ],
            2 => [
                'sector_vocabulary'    => '',
                'vocabulary_uri'       => '',
                'sector_code'          => '',
                'sector_category_code' => '',
                'sector_text'          => 'BC',
                'percentage'           => '',
                'narrative'            => [
                    0 => [
                        'narrative' => 'Central government administration',
                        'language'  => ''
                    ]
                ]
            ]
        ];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('sector');
        $sector   = [];
        foreach ($data as $element) {
            $sector = $this->activity->sector($element, $template);
        }
        $this->assertEquals($expectedSector, $sector);
    }

    public function testCountryBudgetItems()
    {
        $expectedCountryBudget = [
            0 => [
                'vocabulary'  => 'XXX',
                'budget_item' => [
                    0 => [
                        'code_text'   => 'XXX',
                        'code'        => '',
                        'percentage'  => '100',
                        'description' => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'Narrative',
                                        'language'  => 'en'
                                    ],
                                    1 => [
                                        'narrative' => 'Narrative II',
                                        'language'  => ''
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $template              = $this->loadTemplate();
        $data                  = $this->loadSpecificElementData('countryBudgetItems');
        $countryBudgetItems    = [];
        foreach ($data as $element) {
            $countryBudgetItems = $this->activity->countryBudgetItems($element, $template);
        }
        $this->assertEquals($expectedCountryBudget, $countryBudgetItems);
    }

    public function testPolicyMarker()
    {
        $expectedPolicyMarker = [
            0 => [
                'vocabulary'     => '1',
                'vocabulary_uri' => '',
                'significance'   => '2',
                'policy_marker'  => '4',
                'narrative'      => [
                    0 => [
                        'narrative' => 'Trade Development',
                        'language'  => 'en'
                    ]
                ]
            ],
            1 => [
                'vocabulary'     => '1',
                'vocabulary_uri' => '',
                'significance'   => '2',
                'policy_marker'  => '4',
                'narrative'      => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ];

        $template     = $this->loadTemplate();
        $data         = $this->loadSpecificElementData('policyMarker');
        $policyMarker = [];
        foreach ($data as $element) {
            $policyMarker = $this->activity->policyMarker($element, $template);
        }
        $this->assertEquals($expectedPolicyMarker, $policyMarker);
    }

    public function testBudget()
    {
        $expectedBudget = [
            0 => [
                'budget_type'  => '1',
                'status'       => '',
                'period_start' => [
                    0 => ['date' => '2011-01-01']
                ],
                'period_end'   => [
                    0 => ['date' => '2011-12-31']
                ],
                'value'        => [
                    0 => [
                        'amount'     => '700000',
                        'currency'   => 'GBP',
                        'value_date' => '2010-10-01'
                    ]
                ]
            ]
        ];
        $template       = $this->loadTemplate();
        $data           = $this->loadSpecificElementData('budget');
        $budget         = [];
        foreach ($data as $element) {
            $budget = $this->activity->budget($element, $template);
        }
        $this->assertEquals($expectedBudget, $budget);
    }

    public function testPlannedDisbursement()
    {
        $expectedPlannedDisbursement = [
            0 => [
                'planned_disbursement_type' => '',
                'period_start'              => [
                    0 => ['date' => '2011-01-01']
                ],
                'period_end'                => [
                    0 => ['date' => '2011-12-31']
                ],
                'value'                     => [
                    0 => [
                        'amount'     => '700000',
                        'currency'   => 'GBP',
                        'value_date' => '2010-10-01'
                    ]
                ],
                'provider_org'              => [
                    0 => [
                        'ref'         => '',
                        'activity_id' => '',
                        'type'        => '',
                        'narrative'   => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'receiver_org'              => [
                    0 => [
                        'ref'         => '',
                        'activity_id' => '',
                        'type'        => '',
                        'narrative'   => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ]
            ],
            1 => [
                'planned_disbursement_type' => '',
                'period_start'              => [
                    0 => ['date' => '2012-01-01']
                ],
                'period_end'                => [
                    0 => ['date' => '2013-12-31']
                ],
                'value'                     => [
                    0 => [
                        'amount'     => '7000000',
                        'currency'   => 'GBP',
                        'value_date' => '2012-10-01'
                    ]
                ],
                'provider_org'              => [
                    0 => [
                        'ref'         => '',
                        'activity_id' => '',
                        'type'        => '',
                        'narrative'   => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'receiver_org'              => [
                    0 => [
                        'ref'         => '',
                        'activity_id' => '',
                        'type'        => '',
                        'narrative'   => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $template                    = $this->loadTemplate();
        $data                        = $this->loadSpecificElementData('plannedDisbursement');
        $plannedDisbursement         = [];
        foreach ($data as $element) {
            $plannedDisbursement = $this->activity->plannedDisbursement($element, $template);
        }
        $this->assertEquals($expectedPlannedDisbursement, $plannedDisbursement);
    }

    public function testDocumentLink()
    {
        $expectedDocumentLink = [
            0 => [
                'url'           => 'http://www.aidtransparency.net/wp-content/uploads/2009/06/Summary-IATI-Standard-Version-1-Final.doc',
                'format'        => 'application/msword',
                'title'         => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Grant Performance Report',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Grant Performance Report',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'category'      => [
                    0 => [
                        'code' => 'A04'
                    ],
                    1 => [
                        'code' => 'A05'
                    ]
                ],
                'language'      => [
                    0 => [
                        'language' => 'fr'
                    ],
                    1 => [
                        'language' => 'en'
                    ]
                ],
                'document_date' => [
                    0 => [
                        'date' => ''
                    ]
                ]
            ]
        ];

        $template     = $this->loadTemplate();
        $data         = $this->loadSpecificElementData('documentLink');
        $documentLink = [];
        foreach ($data as $element) {
            $documentLink = $this->activity->documentLink($element, $template);
        }
        $this->assertEquals($expectedDocumentLink, $documentLink);
    }

    public function testRelatedActivity()
    {
        $expectedRelatedActivity = [
            0 => [
                'relationship_type'   => '1',
                'activity_identifier' => 'GB-1-105838'
            ]
        ];

        $template        = $this->loadTemplate();
        $data            = $this->loadSpecificElementData('relatedActivity');
        $relatedActivity = [];
        foreach ($data as $element) {
            $relatedActivity = $this->activity->relatedActivity($element, $template);
        }
        $this->assertEquals($expectedRelatedActivity, $relatedActivity);
    }

    public function testConditions()
    {
        $expectedConditions = [
            'condition_attached' => '1',
            'condition'          => [
                0 => [
                    'condition_type' => '1',
                    'narrative'      => [
                        0 => [
                            'narrative' => 'Example text here.',
                            'language'  => ''
                        ]
                    ]
                ],
                1 => [
                    'condition_type' => '2',
                    'narrative'      => [
                        0 => [
                            'narrative' => 'Example text here.',
                            'language'  => 'en'
                        ]
                    ]
                ]
            ]
        ];

        $template   = $this->loadTemplate();
        $data       = $this->loadSpecificElementData('conditions');
        $conditions = [];
        foreach ($data as $element) {
            $conditions = $this->activity->conditions($element, $template);
        }
        $this->assertEquals($expectedConditions, $conditions);
    }

    public function testLegacyData()
    {
        $expectedLegacyData = [
            0 => [
                'name'            => 'adsadasd',
                'value'           => '1312312',
                'iati_equivalent' => 'asdasdas'
            ],
            1 => [
                'name'            => 'adsadasd',
                'value'           => '1312312',
                'iati_equivalent' => ''
            ]
        ];

        $template   = $this->loadTemplate();
        $data       = $this->loadSpecificElementData('legacyData');
        $legacyData = [];
        foreach ($data as $element) {
            $legacyData = $this->activity->legacyData($element, $template);
        }
        $this->assertEquals($expectedLegacyData, $legacyData);
    }

    public function loadSpecificElementData($elementName)
    {
        $data = [];
        foreach ($this->data as $activity) {
            foreach ($activity['value'] as $element) {
                if ($this->name($element) == $elementName) {
                    $data[] = $element;
                }
            }
        }

        return $data;
    }

    /**
     * @param      $element
     * @param bool $snakeCase
     * @return string
     */
    protected function name($element, $snakeCase = false)
    {
        if (is_array($element)) {
            $camelCaseString = camel_case(str_replace('{}', '', $element['name']));

            return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
        }

        $camelCaseString = camel_case(str_replace('{}', '', $element));

        return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
    }

    public function loadTemplate()
    {
        return $this->template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}