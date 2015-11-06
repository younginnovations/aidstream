<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Budget;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;


/**
 * Class BudgetTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class BudgetTest extends AidStreamTestCase
{

    protected $activity;
    protected $budget;

    public function setup()
    {
        parent::setUp();
        $this->activity = m::mock(Activity::class);
        $this->budget   = new Budget($this->activity);
    }

    public function testItShouldUpdateBudget()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('budget', 'testBudget');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->budget->update(['budget' => 'testBudget'], $this->activity)
        );
    }

    public function testItShouldReturnBudgetDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('findorFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'budget'
        )->andReturn([]);
        $this->assertTrue(is_array($this->budget->getBudgetData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
