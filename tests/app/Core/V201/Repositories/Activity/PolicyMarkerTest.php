<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\PolicyMarker;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class PolicyMarkerTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class PolicyMarkerTest extends AidStreamTestCase
{

    protected $activity;
    protected $policyMarker;

    public function setup()
    {
        parent::setUp();
        $this->activity     = m::mock(Activity::class);
        $this->policyMarker = new PolicyMarker($this->activity);
    }

    public function testItShouldUpdatePolicyMarker()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('policy_marker', 'testPolicy');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->policyMarker->update(['policy_marker' => 'testPolicy'], $this->activity)
        );
    }

    public function testItShouldReturnPolicyMarkerDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('find')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'policy_marker'
        )->andReturn([]);
        $this->assertTrue(is_array($this->policyMarker->getpolicyMarkerData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
