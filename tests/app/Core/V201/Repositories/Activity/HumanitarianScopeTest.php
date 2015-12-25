<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V202\Repositories\Activity\HumanitarianScope;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class HumanitarianScopeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class HumanitarianScopeTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activity;
    protected $humanitarianScope;

    public function setUp()
    {
        parent::setUp();
        $this->activity          = m::mock(Activity::class);
        $this->humanitarianScope = new HumanitarianScope($this->activity);
    }

    public function testItShouldUpdateActivityHumanitarianScope()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('humanitarian_scope', 'testHumanitarianScope');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->humanitarianScope->update(['humanitarian_scope' => 'testHumanitarianScope'], $this->activity));
    }

    public function testItShouldReturnHumanitarianScopeData()
    {
        $this->activity->shouldReceive('find')->once()->with(1)->andReturnSelf()->shouldReceive('getAttribute')->once()->with('humanitarian_scope')->andReturn([]);
        $this->assertTrue(is_array($this->humanitarianScope->getActivityHumanitarianScopeData(1)));
    }

    public function testItShouldReturnActivityDataWithCertainActivityId()
    {
        $this->activity->shouldReceive('find')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->humanitarianScope->getActivityData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
