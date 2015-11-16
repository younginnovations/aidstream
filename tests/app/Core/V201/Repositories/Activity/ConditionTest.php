<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Condition;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class ConditionTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class ConditionTest extends AidStreamTestCase
{

    protected $activity;
    protected $condition;

    public function setup()
    {
        parent::setUp();
        $this->activity  = m::mock(Activity::class);
        $this->condition = new Condition($this->activity);
    }

    public function testItShouldUpdateCondition()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('conditions', ['testConditions']);
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->condition->update(['testConditions'], $this->activity)
        );
    }

    public function testItShouldReturnConditionDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'conditions'
        )->andReturn([]);
        $this->assertTrue(is_array($this->condition->getConditionData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
