<?php namespace Test\app\Core\V201\Wizard\Repositories\Activity;

use App\Core\V201\Wizard\Repositories\Activity\StepTwo;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class StepTwoTest
 * @package Test\app\Core\V201\Wizard\Repositories\Activity
 */
class StepTwoTest extends AidStreamTestCase
{
    protected $activityModel;
    protected $stepTwo;

    public function setUp()
    {
        parent::setUp();
        $this->activityModel = m::mock(Activity::class);
        $this->stepTwo       = new StepTwo($this->activityModel);
    }

    /**
     * @test
     */
    public function testItShouldUpdateStepTwoActivityElements()
    {
        $this->activityModel->shouldReceive('setAttribute')->once()->with('title', 'testTitle');
        $this->activityModel->shouldReceive('setAttribute')->once()->with('description', 'testDescription');
        $this->activityModel->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->stepTwo->update(['title' => 'testTitle', 'description' => 'testDescription'], $this->activityModel)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
