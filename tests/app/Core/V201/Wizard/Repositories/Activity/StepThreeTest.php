<?php namespace Test\app\Core\V201\Wizard\Repositories\Activity;

use App\Core\V201\Wizard\Repositories\Activity\StepThree;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class StepThreeTest
 * @package Test\app\Core\V201\Wizard\Repositories\Activity
 */
class StepThreeTest extends AidStreamTestCase
{
    protected $activityModel;
    protected $stepThree;

    public function setUp()
    {
        parent::setUp();
        $this->activityModel = m::mock(Activity::class);
        $this->stepThree     = new StepThree($this->activityModel);
    }

    /**
     * @test
     */
    public function testItShouldUpdateStepThreeActivityElements()
    {
        $this->activityModel->shouldReceive('setAttribute')->once()->with('activity_status', 'activityStatus');
        $this->activityModel->shouldReceive('setAttribute')->once()->with('activity_date', 'activityDate');
        $this->activityModel->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->stepThree->update(
                ['activity_status' => 'activityStatus', 'activity_date' => 'activityDate'],
                $this->activityModel
            )
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
