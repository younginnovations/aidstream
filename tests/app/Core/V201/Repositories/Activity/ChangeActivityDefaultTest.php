<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\ChangeActivityDefault;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ChangeActivityDefaultTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class ChangeActivityDefaultTest extends AidStreamTestCase
{

    protected $activity;
    protected $changeActivityDefault;

    public function setUp()
    {
        parent::setUp();
        $this->activity              = m::mock(Activity::class);
        $this->changeActivityDefault = new ChangeActivityDefault($this->activity);
    }

    public function testItShouldUpdateActivityDefaultValues()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('default_field_values', []);
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->changeActivityDefault->update([], $this->activity));
    }

    public function testItShouldReturnActivityDefaultValues()
    {
        $this->activity->shouldReceive('find')->with(1)->andReturn($this->activity);
        $this->activity->shouldReceive('getAttribute')->once()->with('default_field_values')->andReturn([]);
        $this->assertTrue(is_array($this->changeActivityDefault->getActivityDefaultValues(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
