<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\CountryBudgetItem;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class CountryBudgetItemTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class CountryBudgetItemTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $countryBudgetItem;

    public function setUp()
    {
        parent::setUp();
        $this->activityData      = m::mock(Activity::class);
        $this->countryBudgetItem = new CountryBudgetItem($this->activityData);
    }

    public function testItShouldUpdateActivityCountryBudgetItem()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'country_budget_items',
            'testCountryBudgetItem'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->countryBudgetItem->update(['country_budget_item' => 'testCountryBudgetItem'], $this->activityData)
        );

    }

    public function testItShouldReturnCountryBudgetItemData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('country_budget_items')->andReturn([]);
        $this->assertTrue(is_array($this->countryBudgetItem->getCountryBudgetItemData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
