<?php

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Activity;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlErrorServiceProvider;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;
use Sabre\Xml\Service;
use Test\AidStreamTestCase;

class V202ActivityDataMappingTest extends AidStreamTestCase
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
        $xml                           = file_get_contents('tests/app/xmlImporter/xml/V202.xml');
        $this->data                    = $this->xmlServiceProvider->load($xml);
        $this->template                = $template = json_decode(file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json')), true);
    }

    public function testIatiIdentifierData()
    {
        $expectedIdentifier = [
            'activity_identifier'  => '',
            'iati_identifier_text' => 'GB-CHC-1148404-4104-1_CS-HR-NMFA'
        ];
        $template           = $this->loadTemplate();
        $data               = $this->loadSpecificElementData('iatiIdentifier');
        foreach ($data as $element) {
            $this->assertEquals($expectedIdentifier, $this->activity->iatiIdentifier($element, $template));
        }
    }

    public function testTitle()
    {
        $expectedTitle = [
            0 => [
                'narrative' => 'Test Title 1',
                'language'  => ''
            ],
            1 => [
                'narrative' => 'Test Title 2',
                'language'  => 'fr'
            ]
        ];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('title');
        foreach ($data as $element) {
            $this->assertEquals($expectedTitle, $this->activity->title($element, $template));
        }
    }

    public function testReportingOrg()
    {
        $expectedIdentifier         = [
            'activity_identifier'  => '4104-1_CS-HR-NMFA',
            'iati_identifier_text' => 'GB-CHC-1148404-4104-1_CS-HR-NMFA'
        ];
        $this->activity->identifier = [
            'activity_identifier'  => '',
            'iati_identifier_text' => 'GB-CHC-1148404-4104-1_CS-HR-NMFA'
        ];

        $template = $this->loadTemplate();
        $data     = $this->loadSpecificElementData('reportingOrg');
        foreach ($data as $element) {
            $this->assertEquals($expectedIdentifier, $this->activity->reportingOrg($element, $template));
        }
    }

    public function testDescription()
    {
        $expectedDescription = [
            1 => [
                'type'      => '1',
                'narrative' => [
                    0 => [
                        'narrative' => 'Description of type 1',
                        'language'  => 'en'
                    ],
                    1 => [
                        'narrative' => 'TEST',
                        'language'  => ''
                    ]
                ]
            ],
            2 => [
                'type'      => '2',
                'narrative' => [
                    0 => [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ];
        $template            = $this->loadTemplate();
        $data                = $this->loadSpecificElementData('description');
        $descriptions        = [];
        foreach ($data as $element) {
            $type         = $this->activity->attributes($element, 'type');
            $descriptions = $this->activity->description($element, $template);
        }
        $this->assertEquals($expectedDescription, $descriptions);
    }

    public function testParticipatingOrg()
    {
        $expectedParticipatingOrg = [
            0 => [
                'organization_role' => '1',
                'identifier'        => 'XM-DAC-7',
                'organization_type' => '10',
                'activity_id'       => 'XM-DAC-7-28820',
                'narrative'         => [
                    0 => [
                        'narrative' => 'Netherlands - Ministry of Foreign Affairs',
                        'language'  => 'en'
                    ],
                    1 => [
                        'narrative' => 'Netherlands - Ministry of Foreign Affairs',
                        'language'  => ''
                    ]
                ]
            ],
            1 => [
                'organization_role' => '2',
                'identifier'        => 'GB-CHC-1148404',
                'organization_type' => '21',
                'activity_id'       => '4104-1_CS-HR-NMFA',
                'narrative'         => [
                    0 => [
                        'narrative' => 'Internews Europe',
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

    public function testActivityStatus()
    {
        $expectedStatus = 2;
        $template       = $this->loadTemplate();
        $data           = $this->loadSpecificElementData('activityStatus');
        foreach ($data as $element) {
            $activityStatus = $this->activity->activityStatus($element, $template);
        }
        $this->assertEquals($expectedStatus, $this->activity->activityStatus($element, $template));
    }

    public function testActivityDate()
    {
        $expectedActivityDate = [
            0 => [
                'date'      => '2016-08-22',
                'type'      => '2',
                'narrative' => [
                    0 => [
                        'narrative' => 'First disbursement',
                        'language'  => 'en'
                    ]
                ]
            ],
            1 => [
                'date'      => '2018-09-30',
                'type'      => '3',
                'narrative' => [
                    0 => [
                        'narrative' => 'Final disbursement',
                        'language'  => 'en'
                    ],
                    1 => [
                        'narrative' => 'Final disbursement II',
                        'language'  => ''
                    ]
                ]
            ]
        ];
        $template             = $this->loadTemplate();
        $data                 = $this->loadSpecificElementData('activityDate');
        $activityDates        = [];
        foreach ($data as $element) {
            $activityDates = $this->activity->activityDate($element, $template);
        }
        $this->assertEquals($expectedActivityDate, $activityDates);

    }

    public function testActivityScope()
    {
        $expectedActivityScope = "4";
        $template              = $this->loadTemplate();
        $data                  = $this->loadSpecificElementData('activityScope');
        $activityScope         = "";
        foreach ($data as $element) {
            $activityScope = $this->activity->activityScope($element, $template);
        }
        $this->assertEquals($expectedActivityScope, $activityScope);
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
                                'narrative' => 'Internews Europe',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'department'      => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Operations',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'person_name'     => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Alissa Konstantinova',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'job_title'       => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Contracts and Compliance Manager',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'telephone'       => [
                    0 => [
                        'telephone' => '+44 (0)207 566 3300'
                    ],
                    1 => [
                        'telephone' => '9849841176'
                    ]
                ],
                'email'           => [
                    0 => [
                        'email' => 'info@internews.eu'
                    ]
                ],
                'website'         => [
                    0 => [
                        'website' => 'http://internews.org/'
                    ]
                ],
                'mailing_address' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'New City Cloisters 196 Old Street London EC1V 9FR United Kingdom',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $template            = $this->loadTemplate();
        $data                = $this->loadSpecificElementData('contactInfo');
        $contactInfo         = [];
        foreach ($data as $element) {
            $contactInfo = $this->activity->contactInfo($element, $template);
        }
        $this->assertEquals($expectedContactInfo, $contactInfo);
    }

    public function testSector()
    {
        $expectedSector = [
            0 => [
                'sector_vocabulary'    => '1',
                'vocabulary_uri'       => '',
                'sector_code'          => '22040',
                'sector_category_code' => '',
                'sector_text'          => '',
                'percentage'           => '20',
                'narrative'            => [
                    0 => [
                        'narrative' => 'R1.1 Establishment of Internet access centres (hubs) in provinces that are the most affected by violence',
                        'language'  => 'en'
                    ]
                ]
            ],
            1 => [
                'sector_vocabulary'    => '1',
                'vocabulary_uri'       => '',
                'sector_code'          => '22040',
                'sector_category_code' => '',
                'sector_text'          => '',
                'percentage'           => '20',
                'narrative'            => [
                    0 => [
                        'narrative' => 'R2.1. Provision of tailor-made secured communication solutions for media outlets including secure hosting, VPN provision',
                        'language'  => 'fr'
                    ]
                ]
            ],
            2 => [
                'sector_vocabulary'    => '3',
                'vocabulary_uri'       => '',
                'sector_code'          => '',
                'sector_category_code' => '',
                'sector_text'          => '15153',
                'percentage'           => '80',
                'narrative'            => [
                    0 => [
                        'narrative' => 'Vocab 3',
                        'language'  => 'en'
                    ],
                    1 => [
                        'narrative' => '????',
                        'language'  => ''
                    ]
                ]
            ],
            3 => [
                'sector_vocabulary'    => '2',
                'vocabulary_uri'       => '',
                'sector_code'          => '',
                'sector_category_code' => '15153',
                'sector_text'          => '',
                'percentage'           => '80',
                'narrative'            => [
                    0 => [
                        'narrative' => '',
                        'language'  => 'en'
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

    public function testBudget()
    {
        $expectedBudget = [
            0 => [
                'budget_type'  => '1',
                'status'       => '2',
                'period_start' => [
                    0 => ['date' => '2016-06-01']
                ],
                'period_end'   => [
                    0 => ['date' => '2017-05-31']
                ],
                'value'        => [
                    0 => [
                        'amount'     => '490442.5',
                        'currency'   => 'EUR',
                        'value_date' => '2016-08-22'
                    ]
                ]
            ],
            1 => [
                'budget_type'  => '1',
                'status'       => '1',
                'period_start' => [
                    0 => ['date' => '2017-06-01']
                ],
                'period_end'   => [
                    0 => ['date' => '2018-05-31']
                ],
                'value'        => [
                    0 => [
                        'amount'     => '490442.5',
                        'currency'   => 'EUR',
                        'value_date' => '2018-09-30'
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

    public function testRecipientCountry()
    {
        $expectedRecipientCountry = [
            0 => [
                'country_code' => 'AL',
                'percentage'   => '',
                'narrative'    => [
                    0 => [
                        'narrative' => 'albania',
                        'language'  => ''
                    ]
                ]
            ],
            1 => [
                'country_code' => 'NP',
                'percentage'   => '50',
                'narrative'    => [
                    0 => [
                        'narrative' => 'nepal',
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
                'region_code'       => '88',
                'region_vocabulary' => '99',
                'vocabulary_uri'    => 'http://www.google.com',
                'percentage'        => '50',
                'narrative'         => [
                    0 => [
                        'narrative' => 'recipient region',
                        'language'  => ''
                    ],
                    1 => [
                        'narrative' => 'recipient region II',
                        'language'  => 'ae'
                    ]
                ]
            ],
            1 => [
                'region_code'       => '679',
                'region_vocabulary' => '2',
                'vocabulary_uri'    => 'http://www.facebook.com',
                'percentage'        => '50',
                'narrative'         => [
                    0 => [
                        'narrative' => 'part II',
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
                'reference'            => 'ref123',
                'location_reach'       => [0 => ['code' => '1']],
                'location_id'          => [0 => ['vocabulary' => 'A3', 'code' => '123123123']],
                'name'                 => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Location Name',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Locatioe Name',
                                'language'  => 'am'
                            ]
                        ]
                    ]
                ],
                'location_description' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Location descripiton',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Location Description OO',
                                'language'  => 'kw'
                            ]
                        ]
                    ]
                ],
                'activity_description' => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Location Description',
                                'language'  => 'he'
                            ]
                        ]
                    ]
                ],
                'administrative'       => [
                    0 => [
                        'vocabulary' => 'A1',
                        'code'       => '111110',
                        'level'      => '10'
                    ]
                ],
                'point'                => [
                    0 => [
                        'srs_name' => 'http://www.opengis.net/def/crs/EPSG/0/4326',
                        'position' => [
                            0 => [
                                'latitude'  => '12',
                                'longitude' => '0'
                            ]
                        ]
                    ]
                ],
                'exactness'            => [
                    0 => ['code' => '2']
                ],
                'location_class'       => [
                    0 => ['code' => '3']
                ],
                'feature_designation'  => [
                    0 => ['code' => 'MFGQ']
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

    public function testPlannedDisbursement()
    {
        $expectedPlannedDisbursement = [
            0 => [
                'planned_disbursement_type' => '1',
                'period_start'              => [
                    0 => ['date' => '2018-01-01']
                ],
                'period_end'                => [
                    0 => ['date' => '2018-05-31']
                ],
                'value'                     => [
                    0 => [
                        'amount'     => '49045',
                        'currency'   => 'EUR',
                        'value_date' => '2018-09-30'
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
                'planned_disbursement_type' => '1',
                'period_start'              => [
                    0 => ['date' => '2017-01-01']
                ],
                'period_end'                => [
                    0 => ['date' => '2017-12-31']
                ],
                'value'                     => [
                    0 => [
                        'amount'     => '681840',
                        'currency'   => 'EUR',
                        'value_date' => '2017-05-31'
                    ]
                ],
                'provider_org'              => [
                    0 => [
                        'ref'         => 'provider  ref',
                        'activity_id' => 'actiivity  id',
                        'type'        => '22',
                        'narrative'   => [
                            0 => [
                                'narrative' => 'this is provider org',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'provider-org asd',
                                'language'  => 'he'
                            ]
                        ]
                    ]
                ],
                'receiver_org'              => [
                    0 => [
                        'ref'         => 'receiver  ref',
                        'activity_id' => 'receiver  activity',
                        'type'        => '22',
                        'narrative'   => [
                            0 => [
                                'narrative' => 'receiver name',
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

    public function testCountryBudgetItems()
    {
        $expectedCountryBudget = [
            0 => [
                'vocabulary'  => '1',
                'budget_item' => [
                    0 => [
                        'code_text'   => '',
                        'code'        => '1.3.1',
                        'percentage'  => '50',
                        'description' => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => '',
                                        'language'  => ''
                                    ]
                                ]
                            ]
                        ]
                    ],
                    1 => [
                        'code_text'   => '',
                        'code'        => '1.2.1',
                        'percentage'  => '50',
                        'description' => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'Country budget Description',
                                        'language'  => ''
                                    ],
                                    1 => [
                                        'narrative' => 'Country budget DescII',
                                        'language'  => 'da'
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
                'vocabulary_uri' => 'http://www.google.com',
                'significance'   => '1',
                'policy_marker'  => '3',
                'narrative'      => [
                    0 => [
                        'narrative' => 'policy marker text',
                        'language'  => ''
                    ],
                    1 => [
                        'narrative' => 'policy marker text II',
                        'language'  => 'en'
                    ]
                ]
            ],
            1 => [
                'vocabulary'     => '1',
                'vocabulary_uri' => '',
                'significance'   => '',
                'policy_marker'  => '3',
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

    public function testConditions()
    {
        $expectedConditions = [
            'condition_attached' => '0',
            'condition'          => [
                0 => [
                    'condition_type' => '',
                    'narrative'      => [
                        0 => [
                            'narrative' => '',
                            'language'  => ''
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
                'name'            => 'asdasasdasd',
                'value'           => '12312312312',
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

    public function testHumanitarianScope()
    {
        $expectedHumanitairanScope = [
            0 => [
                'type'           => '1',
                'vocabulary'     => '99',
                'vocabulary_uri' => 'http://www.google.com',
                'code'           => '123123',
                'narrative'      => [
                    0 => [
                        'narrative' => 'humanitarian scope',
                        'language'  => ''
                    ],
                    1 => [
                        'narrative' => 'humanitarian scope II',
                        'language'  => 'af'
                    ]
                ]
            ],
            1 => [
                'type'           => '2',
                'vocabulary'     => '99',
                'vocabulary_uri' => 'http://www.google.com',
                'code'           => '12837123',
                'narrative'      => [
                    0 => [
                        'narrative' => 'fghjklds;as;dasdasd',
                        'language'  => ''
                    ]
                ]
            ]
        ];

        $template          = $this->loadTemplate();
        $data              = $this->loadSpecificElementData('humanitarianScope');
        $humanitarianScope = [];
        foreach ($data as $element) {
            $humanitarianScope = $this->activity->humanitarianScope($element, $template);
        }
        $this->assertEquals($expectedHumanitairanScope, $humanitarianScope);
    }

    public function testRelatedActivity()
    {
        $expectedRelatedActivity = [
            0 => [
                'relationship_type'   => '3',
                'activity_identifier' => '1213132'
            ],
            1 => [
                'relationship_type'   => '4',
                'activity_identifier' => '123123'
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

    public function testDocumentLink()
    {
        $expectedDocumentLink = [
            0 => [
                'url'           => 'https://s3-us-west-2.amazonaws.com/aidstream-demo/documents/333/AidStream-new-20160411120437.pdf',
                'format'        => 'application/activemessage',
                'title'         => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Stored in s3',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Stored in S3 II',
                                'language'  => 'co'
                            ]
                        ]
                    ]
                ],
                'category'      => [
                    0 => [
                        'code' => 'A01'
                    ]
                ],
                'language'      => [
                    0 => [
                        'language' => 'ae'
                    ]
                ],
                'document_date' => [
                    0 => [
                        'date' => '2016-11-08'
                    ]
                ]
            ],
            1 => [
                'url'           => 'http://www.google.com.np',
                'format'        => 'application/atomsvc+xml',
                'title'         => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'asdasdasdasdasd',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'category'      => [
                    0 => [
                        'code' => 'A03'
                    ]
                ],
                'language'      => [
                    0 => [
                        'language' => ''
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

}