<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\CollaborationType;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class CollaborationTypeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class CollaborationTypeTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $collaborationType;

    public function setUp()
    {
        parent::setUp();
        $this->activityData      = m::mock(Activity::class);
        $this->collaborationType = new CollaborationType($this->activityData);
    }

    public function testItShouldUpdateActivityCollaborationType()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'collaboration_type',
            'testCollaborationType'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->collaborationType->update(['collaboration_type' => 'testCollaborationType'], $this->activityData)
        );

    }

    public function testItShouldReturnCollaborationTypeData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('collaboration_type')->andReturn([]);
        $this->assertTrue(is_array($this->collaborationType->getCollaborationTypeData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
