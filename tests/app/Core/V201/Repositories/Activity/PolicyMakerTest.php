<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\PolicyMaker;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class PolicyMakerTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class PolicyMakerTest extends AidStreamTestCase
{

    protected $activity;
    protected $policyMaker;

    public function setup()
    {
        parent::setUp();
        $this->activity    = m::mock(Activity::class);
        $this->policyMaker = new PolicyMaker($this->activity);
    }

    public function testItShouldUpdatePolicyMaker()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('policy_maker', 'testPolicy');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->policyMaker->update(['policy_maker' => 'testPolicy'], $this->activity)
        );
    }

    public function testItShouldReturnPolicyMakerDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('find')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'policy_maker'
        )->andReturn([]);
        $this->assertTrue(is_array($this->policyMaker->getpolicyMakerData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
