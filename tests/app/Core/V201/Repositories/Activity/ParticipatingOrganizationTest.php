<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\ParticipatingOrganization;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ParticipatingOrganizationTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class ParticipatingOrganizationTest extends AidStreamTestCase
{

    protected $activityData;
    protected $participatingOrganization;

    public function setUp()
    {
        parent::setUp();
        $this->activityData              = m::mock(Activity::class);
        $this->participatingOrganization = new ParticipatingOrganization($this->activityData);
    }

    public function testItShouldUpdateActivityParticipatingOrganizationData()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with('participating_organization', 'testOrg');
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->participatingOrganization->update(['participating_organization' => 'testOrg'], $this->activityData)
        );
    }

    public function testItShouldReturnActivityParticipatingOrganizationDataWithSpecificActivityId()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'participating_organization'
        )->andReturn([]);
        $this->assertTrue(is_array($this->participatingOrganization->getParticipatingOrganizationData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
