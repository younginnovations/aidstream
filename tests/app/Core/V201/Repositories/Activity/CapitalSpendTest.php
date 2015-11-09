<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\CapitalSpend;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class CapitalSpendTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class CapitalSpendTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $capitalSpend;

    public function setUp()
    {
        parent::setUp();
        $this->activityData = m::mock(Activity::class);
        $this->capitalSpend = new CapitalSpend($this->activityData);
    }

    public function testItShouldUpdateActivityCapitalSpend()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'capital_spend',
            'testCapitalSpend'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->capitalSpend->update(['capital_spend' => 'testCapitalSpend'], $this->activityData)
        );

    }

    public function testItShouldReturnCapitalSpendData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('capital_spend')->andReturn([]);
        $this->assertTrue(is_array($this->capitalSpend->getCapitalSpendData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
