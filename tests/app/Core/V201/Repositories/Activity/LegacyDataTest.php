<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\LegacyData;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class LegacyDataTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class LegacyDataTest extends AidStreamTestCase
{

    protected $activity;
    protected $legacyData;

    public function setup()
    {
        parent::setUp();
        $this->activity   = m::mock(Activity::class);
        $this->legacyData = new LegacyData($this->activity);
    }

    public function testItShouldUpdateLegacyData()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('legacy_data', 'testLegacyData');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->legacyData->update(['legacy_data' => 'testLegacyData'], $this->activity));
    }

    public function testItShouldReturnLegacyDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive('getAttribute')->once()->with('legacy_data')->andReturn([]);
        $this->assertTrue(is_array($this->legacyData->getLegacyData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
