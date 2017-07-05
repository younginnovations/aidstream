<?php namespace Test\app\Services\Organization;

use App\Core\V202\Repositories\Organization\RecipientRegionBudget;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Services\Organization\RecipientRegionBudgetManager;
use Illuminate\Contracts\Logging\Log as dbLogger;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Contracts\Auth\Guard;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class RecipientRegionBudgetManagerTest
 * @package Test\app\Services\Organization
 */
class RecipientRegionBudgetManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $recipientRegionBudgetRepo;
    protected $recipientRegionBudgetManager;
    protected $database;
    protected $orgDataModel;

    public function setUp()
    {
        parent::setUp();
        $this->version                   = m::mock(Version::class);
        $this->dbLogger                  = m::mock(dbLogger::class);
        $this->logger                    = m::mock(Logger::class);
        $this->auth                      = m::mock(Guard::class);
        $this->database                  = m::mock(DatabaseManager::class);
        $this->recipientRegionBudgetRepo = m::mock(RecipientRegionBudget::class);
        $this->orgDataModel              = m::mock(OrganizationData::class);
        $this->version->shouldReceive('getOrganizationElement->getRecipientRegionBudgetRepository')->andReturn($this->recipientRegionBudgetRepo);
        $this->recipientRegionBudgetManager = new RecipientRegionBudgetManager($this->version, $this->auth, $this->database, $this->dbLogger, $this->logger);
    }

    /**
     * @test
     */
    public function testItShouldUpdateRecipientRegionBudgetData()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->once()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->once()->andReturn($user);
        $this->orgDataModel->shouldReceive('getAttribute')->once()->with('recipient_region_budget')->andReturn([]);
        $this->database->shouldReceive('beginTransaction');
        $this->recipientRegionBudgetRepo->shouldReceive('update')->once()->with([], $this->orgDataModel)->andReturn(true);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('info')->once()->with("Organization Recipient Region Budget Updated", ['for' => []]);
        $this->dbLogger->shouldReceive('activity')->once()->with('organization.recipient_region_budget_updated', ['name' => 'organizationName']);
        $this->assertTrue($this->recipientRegionBudgetManager->update([], $this->orgDataModel));
    }

    /**
     * @test
     */
    public function testItShouldGetAllOrganizationDataWithCertainId()
    {
        $this->recipientRegionBudgetRepo->shouldReceive('getOrganizationData')->with(1)->andReturn($this->orgDataModel);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->recipientRegionBudgetManager->getOrganizationData(1));
    }

    /**
     * @test
     */
    public function testItShouldGetOrganizationRecipientRegionBudgetDataWithCertainId()
    {
        $this->recipientRegionBudgetRepo->shouldReceive('getRecipientRegionBudgetData')->with(1)->andReturn($this->orgDataModel);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->recipientRegionBudgetManager->getRecipientRegionBudgetData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
