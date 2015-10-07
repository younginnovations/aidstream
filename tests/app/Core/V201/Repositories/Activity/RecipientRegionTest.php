<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\RecipientRegion;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class RecipientRegionTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class RecipientRegionTest extends AidStreamTestCase
{

    protected $activityData;
    protected $recipientRegion;

    public function setUp()
    {
        parent::setUp();
        $this->activityData    = m::mock(Activity::class);
        $this->recipientRegion = new RecipientRegion($this->activityData);
    }

    public function testItShouldUpdateRecipientRegion()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with('recipient_region', 'testRegion');
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(

            $this->recipientRegion->update(['recipient_region' => 'testRegion'], $this->activityData)
        );
    }

    public function testItShouldReturnRecipientRegionDataWithSpecificActivityId()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'recipient_region'
        )->andReturn([]);
        $this->assertTrue(is_array($this->recipientRegion->getRecipientRegionData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
