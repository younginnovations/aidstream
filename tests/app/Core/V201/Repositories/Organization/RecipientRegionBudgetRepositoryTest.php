<?php namespace Test\app\Core\V201\Repositories\Organization;

use App\Core\V202\Repositories\Organization\RecipientRegionBudget;
use Mockery as m;
use Test\AidStreamTestCase;

class RecipientRegionBudgetRepositoryTest extends AidStreamTestCase
{

    protected $recipientRegionBudgetRepository;
    protected $organizationData;

    public function setUp()
    {
        parent::setUp();
        $this->organizationData                = m::mock('App\Models\Organization\OrganizationData');
        $this->recipientRegionBudgetRepository = new RecipientRegionBudget($this->organizationData);
    }

    public function testItShouldUpdateOrganizationRecipientRegionBudgetData()
    {
        $this->organizationData->shouldReceive('setAttribute')->once()->with('recipient_region_budget', 'a');
        $this->organizationData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->recipientRegionBudgetRepository->update(['recipient_region_budget' => 'a'], $this->organizationData));
    }

    public function testItShouldGetOrganizationDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where')->once()->with('organization_id', 1)->andReturnSelf();
        $this->organizationData->shouldReceive('first')->andReturn($this->organizationData);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->recipientRegionBudgetRepository->getOrganizationData(1));
    }

    public function testItShouldReturnGetOrganizationRecipientRegionBudgetDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where')->once()->with('organization_id', 1)->andReturnSelf();
        $this->organizationData->shouldReceive('first')->andReturn($this->organizationData);
        $this->organizationData->shouldReceive('getAttribute')->once()->with('recipient_region_budget')->andReturn([]);
        $this->assertTrue(is_array($this->recipientRegionBudgetRepository->getRecipientRegionBudgetData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
