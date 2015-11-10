<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DefaultFlowType;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultFlowTypeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DefaultFlowTypeTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $defaultFlowType;

    public function setUp()
    {
        parent::setUp();
        $this->activityData    = m::mock(Activity::class);
        $this->defaultFlowType = new DefaultFlowType($this->activityData);
    }

    public function testItShouldUpdateActivityDefaultFlowType()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'default_flow_type',
            'testDefaultFlowType'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->defaultFlowType->update(['default_flow_type' => 'testDefaultFlowType'], $this->activityData)
        );

    }

    public function testItShouldReturnDefaultFlowTypeData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('default_flow_type')->andReturn([]);
        $this->assertTrue(is_array($this->defaultFlowType->getDefaultFlowTypeData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
