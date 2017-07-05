<?php namespace tests\app\Core\V201\Parser;

use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Models\Settings;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Core\V201\Parser\ActivityDataParser;

class ActivityDataParserTest extends AidStreamTestCase
{
    protected $activityDataParserMock;
    protected $activityDataParser;

    protected $activity = [
        "activity_identifier"                     => "Test-identifier",
        "activity_title"                          => "Test Title",
        "description_general"                     => "Test General",
        "description_objectives"                  => "Test Objectives",
        "description_target_group"                => "Test Target",
        "description_other"                       => "Test Other",
        "activity_status"                         => 1,
        "actual_start_date"                       => "2016-02-25",
        "actual_end_date"                         => "2016-02-26",
        "planned_start_date"                      => "2016-02-25",
        "planned_end_date"                        => "2016-02-26",
        "funding_participating_organization"      => "Test Funding",
        "implementing_participating_organization" => "Test Implementing",
        "recipient_country"                       => "IN",
        "recipient_region"                        => 88,
        "sector_dac_5digit"                       => 11110,
        "activity_scope"                          => 10
    ];
    protected $activityModel;
    protected $organizationModel;
    protected $settingsModel;
    protected $defaultFieldGroups = [
        [
            'default_collaboration_type' => '',
            'default_flow_type'          => '',
            'default_finance_type'       => '',
            'default_aid_type'           => '',
            'default_tied_status'        => ''
        ]
    ];

    public function setUp()
    {
        parent::setUp();
        $this->activityDataParserMock = m::mock(ActivityDataParser::class);
        $this->activityModel          = m::mock(Activity::class);
        $this->organizationModel      = m::mock(Organization::class);
        $this->settingsModel          = m::mock(Settings::class);
        $this->activityDataParser     = new ActivityDataParser($this->activityModel, $this->organizationModel, $this->settingsModel);

        $this->settingsModel->shouldReceive('where->first')->andReturn($this->settingsModel);
        $this->settingsModel->shouldReceive('getAttribute')->with('default_field_values')->once()->andReturn($this->defaultFieldGroups);
        $this->activityDataParser->init($this->activity);
    }

    public function testItShouldSetIdentifier()
    {
        $reportingOrg = [
            [
                "reporting_organization_identifier" => "test-org-identifier",
                "reporting_organization_type"       => "1",
                "narrative"                         => [
                    [
                        "narrative" => "Test Reporting Organization",
                        "language"  => "en"
                    ]
                ]
            ]
        ];
        $output       = [
            "activity_identifier"  => "Test-identifier",
            "iati_identifier_text" => "test-org-identifier-Test-identifier"
        ];

        $this->organizationModel->shouldReceive('getAttribute')->with('reporting_org')->andReturn($reportingOrg);
        $this->organizationModel->shouldReceive('find')->andReturn($this->organizationModel);

        $this->activityDataParser->setIdentifier();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['identifier']);
    }

    public function testItShouldSetTitle()
    {
        $output = [
            [
                "language"  => "",
                "narrative" => "Test Title"
            ]
        ];
        $this->activityDataParser->setTitle();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['title']);
    }

    public function testItShouldSetDescription()
    {
        $output = [
            [
                "type"      => "1",
                "narrative" => [
                    [
                        "narrative" => "Test General",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "type"      => "2",
                "narrative" => [
                    [
                        "narrative" => "Test Objectives",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "type"      => "3",
                "narrative" => [
                    [
                        "narrative" => "Test Target",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "type"      => "4",
                "narrative" => [
                    [
                        "narrative" => "Test Other",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setDescription();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['description']);
    }

    public function testItShouldSetStatus()
    {
        $output = 1;
        $this->activityDataParser->setStatus();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['activity_status']);
    }

    public function testItShouldSetDate()
    {
        $output = [
            [
                "date"      => "2016-02-25",
                "type"      => 1,
                "narrative" => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "date"      => "2016-02-25",
                "type"      => 2,
                "narrative" => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "date"      => "2016-02-26",
                "type"      => 3,
                "narrative" => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "date"      => "2016-02-26",
                "type"      => 4,
                "narrative" => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setDate();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['activity_date']);
    }

    public function testItShouldSetParticipatingOrg()
    {
        $output = [
            [
                "organization_role" => 1,
                "identifier"        => "",
                "organization_type" => "",
                "narrative"         => [
                    [
                        "narrative" => "Test Funding",
                        "language"  => ""
                    ]
                ]
            ],
            [
                "organization_role" => 4,
                "identifier"        => "",
                "organization_type" => "",
                "narrative"         => [
                    [
                        "narrative" => "Test Implementing",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setParticipatingOrg();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['participating_organization']);
    }

    public function testItShouldSetRecipientCountry()
    {
        $output = [
            [
                "country_code" => "IN",
                "percentage"   => "",
                "narrative"    => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setRecipientCountry();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['recipient_country']);
    }

    public function testItShouldSetRecipientRegion()
    {
        $output = [
            [
                "region_code"       => "88",
                "region_vocabulary" => "",
                "vocabulary-uri"    => "",
                "percentage"        => "",
                "narrative"         => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setRecipientRegion();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['recipient_region']);
    }

    public function testItShouldSetSector()
    {
        $output = [
            [
                "sector_vocabulary"    => "",
                "vocabulary_uri"       => "",
                "sector_code"          => "11110",
                "sector_category_code" => "",
                "sector_text"          => "",
                "percentage"           => "",
                "narrative"            => [
                    [
                        "narrative" => "",
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $this->activityDataParser->setSector();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['sector']);
    }

    public function testItShouldSetScope()
    {
        $output = 10;
        $this->activityDataParser->setScope();
        $this->assertEquals($output, $this->activityDataParser->getActivityData()['activity_scope']);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
