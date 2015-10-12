<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Sector;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class SectorTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class SectorTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $sector;

    public function setUp()
    {
        parent::setUp();
        $this->activityData = m::mock(Activity::class);
        $this->sector       = new Sector($this->activityData);
    }

    public function testItShouldUpdateActivitySector()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with('sector', 'testSector');
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->sector->update(['sector' => 'testSector'], $this->activityData));

    }

    public function testItShouldReturnSectorData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('sector')->andReturn([]);
        $this->assertTrue(is_array($this->sector->getSectorData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
