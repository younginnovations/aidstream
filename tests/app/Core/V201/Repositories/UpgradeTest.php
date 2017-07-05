<?php namespace Test\app\Core\V201\Repositories;

use App\Core\V201\Repositories\Upgrade;
use App\Models\Activity\Activity;
use App\Models\Activity\Transaction;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Illuminate\Support\Collection;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class UpgradeTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class UpgradeTest extends AidStreamTestCase
{
    protected $orgData;
    protected $settings;
    protected $activity;
    protected $upgrade;
    protected $transaction;

    public function setUp()
    {
        parent::setUp();
        $this->settings = m::mock(Settings::class);
        $this->orgData  = m::mock(OrganizationData::class);
        $this->activity = m::mock(Activity::class);
        $this->transaction = m::mock(Transaction::class);
        $this->upgrade  = new Upgrade($this->settings, $this->orgData, $this->activity, $this->transaction);
    }

    /**
     * @test
     */
    public function testItShouldUpgradeDataWithCertainOrganizationAndVersion()
    {
        $this->settings->shouldReceive('where')->with('organization_id', 1)->andReturnSelf();
        $this->settings->shouldReceive('first')->andReturn($this->settings);

        $this->orgData->shouldReceive('where')->with('organization_id', 1)->andReturnSelf();
        $this->orgData->shouldReceive('first')->andReturn($this->orgData);

        $this->activity->shouldReceive('where')->with('organization_id', 1)->andReturnSelf();
        $this->activity->shouldReceive('get')->andReturn(m::mock(Collection::class));

        $this->settings->shouldReceive('getAttribute')->with('default_field_values')->andReturn([['humanitarian' => 1]]);
        $this->settings->shouldReceive('setAttribute')->with('default_field_values')->andReturn([['humanitarian' => 1]]);

        $this->orgData->shouldReceive('getAttribute')->with('total_budget')->andReturn([['status' => 1]]);
        $this->orgData->shouldReceive('getAttribute')->with('recipient_organization_budget')->andReturn([['status' => 1]]);
        $this->orgData->shouldReceive('setAttribute')->with('total_budget')->andReturn([['status' => 1]]);
        $this->orgData->shouldReceive('setAttribute')->with('recipient_organization_budget')->andReturn([['status' => 1]]);

        $collection    = m::mock(Collection::class);
        $arrayIterator = new \ArrayIterator([$this->activity]);
        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->activity->shouldReceive('getAttribute')->with('budget')->andReturn([['status' => 1]]);
        $this->activity->shouldReceive('setAttribute')->with('budget')->andReturn([['status' => 1]]);

        $this->settings->shouldReceive('setAttribute')->with('version')->andReturn('version');

    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
