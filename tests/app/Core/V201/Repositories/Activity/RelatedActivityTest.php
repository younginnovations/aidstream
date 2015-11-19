<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\RelatedActivity;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class RelatedActivityTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class RelatedActivityTest extends AidStreamTestCase
{

    protected $activity;
    protected $relatedActivity;

    public function setup()
    {
        parent::setUp();
        $this->activity        = m::mock(Activity::class);
        $this->relatedActivity = new RelatedActivity($this->activity);
    }

    public function testItShouldUpdateRelatedActivity()
    {
        $this->activity->shouldReceive('setAttribute')->once()->with('related_activity', 'testRelatedActivity');
        $this->activity->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->relatedActivity->update(['related_activity' => 'testRelatedActivity'], $this->activity)
        );
    }

    public function testItShouldReturnRelatedActivityDataWithSpecificActivityId()
    {
        $this->activity->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'related_activity'
        )->andReturn([]);
        $this->assertTrue(is_array($this->relatedActivity->getRelatedActivityData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
