<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DefaultAidType;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultAidTypeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DefaultAidTypeTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $defaultAidType;

    public function setUp()
    {
        parent::setUp();
        $this->activityData   = m::mock(Activity::class);
        $this->defaultAidType = new DefaultAidType($this->activityData);
    }

    public function testItShouldUpdateActivityDefaultAidType()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'default_aid_type',
            'testDefaultAidType'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->defaultAidType->update(['default_aid_type' => 'testDefaultAidType'], $this->activityData)
        );

    }

    public function testItShouldReturnDefaultAidTypeData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('default_aid_type')->andReturn([]);
        $this->assertTrue(is_array($this->defaultAidType->getDefaultAidTypeData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
