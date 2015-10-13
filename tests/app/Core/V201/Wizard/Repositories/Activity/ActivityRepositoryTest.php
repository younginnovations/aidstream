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
        $this->activityModel->shouldReceive('getAttribute')->with('identifier')->andReturn('testIdentifier');
        $this->activityModel->shouldReceive('create')->once()->with(
            ['identifier' => ['identifier' => 'testIdentifier'], 'organization_id' => 1]
        )->andReturn(true);
        $this->assertTrue($this->activityRepo->store(['identifier' => 'testIdentifier'], 1));
    }

    public function testItShouldReturnActivityWithSpecificActivityId()
    {
        $this->activityModel->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->activityRepo->getActivityData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
