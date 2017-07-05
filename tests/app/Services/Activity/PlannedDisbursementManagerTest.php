<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\PlannedDisbursement;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class PlannedDisbursementTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class PlannedDisbursementManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $planned_disbursement;

    public function setup()
    {
        parent::setUp();
        $this->activity             = m::mock(Activity::class);
        $this->planned_disbursement = new PlannedDisbursement($this->activity);
    }

    /**
     * @test
     */
    public function testItShouldUpdatePlannedDisbursement()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('planned_disbursement', 'testPlannedDisbursement');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->planned_disbursement->update(['planned_disbursement' => 'testPlannedDisbursement'], $this->activity)
        );
    }

    /**
     * @test
     */
    public function testItShouldReturnPlannedDisbursementDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('findorFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'planned_disbursement'
        )->andReturn([]);
        $this->assertTrue(is_array($this->planned_disbursement->getPlannedDisbursementData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
