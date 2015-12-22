<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\Elements\CsvReader;
use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\UploadActivity;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Collection;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class UploadActivityTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class UploadActivityTest extends AidStreamTestCase
{

    protected $activity;
    protected $activityRepo;
    protected $organization;
    protected $orgRepo;
    protected $readCsv;
    protected $uploadActivityRepo;

    public function setUp()
    {
        parent::setUp();
        $this->activity           = m::mock(Activity::class);
        $this->activityRepo       = m::mock(ActivityRepository::class);
        $this->organization       = m::mock(Organization::class);
        $this->orgRepo            = m::mock(OrganizationRepository::class);
        $this->readCsv            = m::mock(CsvReader::class);
        $this->uploadActivityRepo = new UploadActivity($this->activity, $this->activityRepo, $this->readCsv, $this->orgRepo, $this->organization);
    }

    public function testItShouldUploadActivity()
    {
        $row = [
            'identifier'                 => '',
            'title'                      => '',
            'description'                => '',
            'activity_status'            => '',
            'participating_organization' => '',
            'recipient_country'          => '',
            'recipient_region'           => '',
            'sector'                     => '',
            'activity_date'              => '',
        ];
        $this->activity->shouldReceive('newInstance')->once()->with($row)->andReturnSelf();
        $this->organization->shouldReceive('activities->save')->once()->with($this->activity)->andReturn(true);
        $this->uploadActivityRepo->upload($row, $this->organization);
    }

    public function testItShouldUpdateActivityByUploadingCSV()
    {
        $row = [
            'identifier'                 => 'testIdentifier',
            'title'                      => 'testTitle',
            'description'                => 'testDescription',
            'activity_status'            => 'testStatus',
            'participating_organization' => 'testOrg',
            'recipient_country'          => 'testCountry',
            'recipient_region'           => 'testRegion',
            'sector'                     => 'testSector',
            'activity_date'              => 'testDate',
        ];
        $this->activityRepo->shouldReceive('getActivityData')->with(1)->andReturn($this->activity);
        $this->activity->shouldReceive('setAttribute')->with('identifier', 'testIdentifier');
        $this->activity->shouldReceive('setAttribute')->with('title', 'testTitle');
        $this->activity->shouldReceive('setAttribute')->with('description', 'testDescription');
        $this->activity->shouldReceive('setAttribute')->with('activity_status', 'testStatus');
        $this->activity->shouldReceive('setAttribute')->with('participating_organization', 'testOrg');
        $this->activity->shouldReceive('setAttribute')->with('recipient_country', 'testCountry');
        $this->activity->shouldReceive('setAttribute')->with('recipient_region', 'testRegion');
        $this->activity->shouldReceive('setAttribute')->with('sector', 'testSector');
        $this->activity->shouldReceive('setAttribute')->with('activity_date', 'testDate');
        $this->activity->shouldReceive('save')->andReturn(true);
        $this->uploadActivityRepo->update($row, 1);
    }

    public function testItShouldFormatFormExcelRow()
    {
        $activityRow = [
            'activity_identifier'                     => 'testIdentifier',
            'activity_title'                          => 'testTitle',
            'description_general'                     => 'testDescription1',
            'description_objectives'                  => 'testDescription2',
            'description_target_group'                => 'testDescription3',
            'description_other'                       => 'testDescription4',
            'activity_status'                         => 'testStatus',
            'funding_participating_organization'      => 'testOrg1',
            'implementing_participating_organization' => 'testOrg2',
            'recipient_country'                       => 'testCountry',
            'recipient_region'                        => 'testRegion',
            'sector_dac_3digit'                       => 'testSector',
            'actual_start_date'                       => 'testDate1',
            'actual_end_date'                         => 'testDate2',
            'planned_start_date'                      => 'testDate3',
            'planned_end_date'                        => 'testDate4',
        ];
        $this->orgRepo->shouldReceive('getOrganization')->with(1)->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('reporting_org')->andReturn(
            [['reporting_organization_identifier' => 'testReportingORg']]
        );
        $this->readCsv->shouldReceive('getActivityHeaders')->with('identifier')->andReturn($activityRow);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('title')->andReturn($activityRow);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('description')->andReturn([[]]);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('participatingOrganization')->andReturn([[]]);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('recipientCountry')->andReturn([[]]);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('recipientRegion')->andReturn([[]]);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('sector')->andReturn([[]]);
        $this->readCsv->shouldReceive('getActivityHeaders')->with('activityDate')->andReturn([[]]);
        $this->activity->shouldReceive('setAttribute')->with('identifier')->andReturn('testIdentifier');
        $this->activity->shouldReceive('setAttribute')->with('activity_title')->andReturn('testTitle');
        $this->activity->shouldReceive('setAttribute')->with('description_general')->andReturn('testDescription1');
        $this->activity->shouldReceive('setAttribute')->with('description_objectives')->andReturn('testDescription2');
        $this->activity->shouldReceive('setAttribute')->with('description_target_group')->andReturn('testDescription3');
        $this->activity->shouldReceive('setAttribute')->with('description_other')->andReturn('testDescription4');
        $this->activity->shouldReceive('setAttribute')->with('activity_status')->andReturn('testStatus');
        $this->activity->shouldReceive('setAttribute')->with('funding_participating_organization')->andReturn('testOrg1');
        $this->activity->shouldReceive('setAttribute')->with('implementing_participating_organization')->andReturn('testOrg2');
        $this->activity->shouldReceive('setAttribute')->with('recipient_country')->andReturn('testCountry');
        $this->activity->shouldReceive('setAttribute')->with('recipient_region')->andReturn('testRegion');
        $this->activity->shouldReceive('setAttribute')->with('sector')->andReturn('testSector');
        $this->activity->shouldReceive('setAttribute')->with('actual_start_date')->andReturn('testDate1');
        $this->activity->shouldReceive('setAttribute')->with('actual_end_date')->andReturn('testDate2');
        $this->activity->shouldReceive('setAttribute')->with('planned_start_date')->andReturn('testDate3');
        $this->activity->shouldReceive('setAttribute')->with('planned_end_date')->andReturn('testDate4');
        $this->assertTrue(is_array($this->uploadActivityRepo->formatFromExcelRow($activityRow, 1)));
    }

    public function testItShouldGetIdentifiersWithOrganizationId()
    {
        $collection    = m::mock(Collection::class);
        $arrayIterator = new \ArrayIterator([$this->activity]);

        $this->activity->shouldReceive('where->get')->andReturn($collection);
        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->activity->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->activity->shouldReceive('getAttribute')->once()->with('identifier')->andReturn(['activity_identifier' => '']);
        $this->assertTrue(is_array($this->uploadActivityRepo->getIdentifiers(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
