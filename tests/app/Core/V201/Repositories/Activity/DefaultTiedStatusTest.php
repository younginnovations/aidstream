<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DefaultTiedStatus;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultTiedStatusTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DefaultTiedStatusTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $defaultTiedStatus;

    public function setUp()
    {
        parent::setUp();
        $this->activityData      = m::mock(Activity::class);
        $this->defaultTiedStatus = new DefaultTiedStatus($this->activityData);
    }

    public function testItShouldUpdateActivityDefaultTiedStatus()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'default_tied_status',
            'testDefaultTiedStatus'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->defaultTiedStatus->update(['default_tied_status' => 'testDefaultTiedStatus'], $this->activityData)
        );

    }

    public function testItShouldReturnDefaultTiedStatusData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('default_tied_status')->andReturn([]);
        $this->assertTrue(is_array($this->defaultTiedStatus->getDefaultTiedStatusData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
