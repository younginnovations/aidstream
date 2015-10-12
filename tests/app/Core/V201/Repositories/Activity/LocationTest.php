<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Location;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class LocationTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class LocationTest extends AidStreamTestCase
{

    protected $activityData;
    protected $location;

    public function setUp()
    {
        parent::setUp();
        $this->activityData              = m::mock(Activity::class);
        $this->location = new Location($this->activityData);
    }

    public function testItShouldUpdateActivityLocationData()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with('location', 'testLocation');
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->location->update(['location' => 'testLocation'], $this->activityData)
        );
    }

    public function testItShouldReturnActivityLocationDataWithSpecificActivityId()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'location'
        )->andReturn([]);
        $this->assertTrue(is_array($this->location->getLocation(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
