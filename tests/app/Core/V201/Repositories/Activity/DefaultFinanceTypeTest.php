<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DefaultFinanceType;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultFinanceTypeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DefaultFinanceTypeTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $defaultFinanceType;

    public function setUp()
    {
        parent::setUp();
        $this->activityData       = m::mock(Activity::class);
        $this->defaultFinanceType = new DefaultFinanceType($this->activityData);
    }

    public function testItShouldUpdateActivityDefaultFinanceType()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'default_finance_type',
            'testDefaultFinanceType'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->defaultFinanceType->update(['default_finance_type' => 'testDefaultFinanceType'], $this->activityData)
        );

    }

    public function testItShouldReturnDefaultFinanceTypeData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('default_finance_type')->andReturn([]);
        $this->assertTrue(is_array($this->defaultFinanceType->getDefaultFinanceTypeData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
