<?php namespace Test\app\Core\V201\Wizard\Repositories\Activity;

use App\Core\V201\Wizard\Repositories\Activity\ActivityRepository;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ActivityRepositoryTest
 * @package Test\app\Core\V201\Wizard\Repositories\Activity
 */
class ActivityRepositoryTest extends AidStreamTestCase
{
    protected $activityModel;
    protected $activityRepo;

    public function setUp()
    {
        parent::setUp();
        $this->activityModel = m::mock(Activity::class);
        $this->activityRepo  = new ActivityRepository($this->activityModel);
    }

    public function testItShouldStoreActivityDataToDatabase()
    {
        $this->activityModel->shouldReceive('create')->once()->with(
            ['identifier' => ['identifier' => 'testIdentifier'], 'default_field_values' => ['defaultFieldValues'], 'organization_id' => 1]
        )->andReturn(true);
        $this->assertTrue($this->activityRepo->store(['identifier' => 'testIdentifier'], ['defaultFieldValues'], 1));
    }

    public function testItShouldReturnActivityWithSpecificActivityId()
    {
        $this->activityModel->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->activityRepo->getActivityData(1));
    }

    public function testItShouldSaveDefaultValuesWithSpecificActivityId()
    {
        $activity = $this->activityModel->shouldReceive('find')->once()->with(1)->andReturn($this->activityModel);
        $activity->shouldReceive('setAttribute')->once()->with('collaboration_type', 'collaborationType');
        $activity->shouldReceive('setAttribute')->once()->with('default_flow_type', 'defaultFlowType');
        $activity->shouldReceive('setAttribute')->once()->with('default_finance_type', 'defaultFinanceType');
        $activity->shouldReceive('setAttribute')->once()->with('default_aid_type', 'defaultAidType');
        $activity->shouldReceive('setAttribute')->once()->with('default_tied_status', 'defaultTiedStatus');
        $activity->shouldReceive('save')->once()->andReturn(true);
        $defaultFieldValues = [
            [
                'default_collaboration_type' => 'collaborationType',
                'default_flow_type'          => 'defaultFlowType',
                'default_finance_type'       => 'defaultFinanceType',
                'default_aid_type'           => 'defaultAidType',
                'default_tied_status'        => 'defaultTiedStatus'
            ]
        ];
        $this->assertTrue($this->activityRepo->saveDefaultValues(1, $defaultFieldValues));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
