<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\RecipientCountry;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class RecipientCountryTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class RecipientCountryTest extends AidStreamTestCase
{

    protected $activityData;
    protected $recipientCountry;

    public function setUp()
    {
        parent::setUp();
        $this->activityData     = m::mock(Activity::class);
        $this->recipientCountry = new RecipientCountry($this->activityData);
    }

    public function testItShouldUpdateRecipientCountry()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with('recipient_country', 'testCountry');
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->recipientCountry->update(['recipient_country' => 'testCountry'], $this->activityData)
        );
    }

    public function testItShouldReturnRecipientCountryDataWithSpecificActivityId()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with(
            'recipient_country'
        )->andReturn([]);
        $this->assertTrue(is_array($this->recipientCountry->getRecipientCountryData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
