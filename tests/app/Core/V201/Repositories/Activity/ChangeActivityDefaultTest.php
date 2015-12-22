<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\ChangeActivityDefault;
use App\Models\Settings;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ChangeActivityDefaultTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class ChangeActivityDefaultTest extends AidStreamTestCase
{

    protected $settings;
    protected $changeActivityDefault;

    public function setUp()
    {
        parent::setUp();
        $this->settings              = m::mock(Settings::class);
        $this->changeActivityDefault = new ChangeActivityDefault($this->settings);
    }

    public function testItShouldUpdateActivityDefaultValues()
    {
        $this->settings->shouldReceive('setAttribute')->once()->with('default_field_values', []);
        $this->settings->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->changeActivityDefault->update([], $this->settings));
    }

    public function testItShouldReturnActivityDefaultValues()
    {
        $this->settings->shouldReceive('where->get')->andReturn($this->settings);
        $this->settings->shouldReceive('getAttribute')->once()->with('default_field_values')->andReturn([]);
        $this->assertTrue(is_array($this->changeActivityDefault->getActivityDefaultValues(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
