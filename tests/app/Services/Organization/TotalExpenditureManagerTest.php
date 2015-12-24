<?php namespace Test\app\Services\Organization;

use App\Core\V202\Repositories\Organization\TotalExpenditure;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Services\Organization\TotalExpenditureManager;
use Illuminate\Contracts\Logging\Log as dbLogger;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Auth\Guard;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class TotalExpenditureManagerTest
 * @package Test\app\Services\Organization
 */
class TotalExpenditureManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $totalExpenditureRepo;
    protected $totalExpenditureManager;
    protected $database;
    protected $orgDataModel;

    public function setUp()
    {
        parent::setUp();
        $this->version              = m::mock(Version::class);
        $this->dbLogger             = m::mock(dbLogger::class);
        $this->logger               = m::mock(Logger::class);
        $this->auth                 = m::mock(Guard::class);
        $this->database             = m::mock(DatabaseManager::class);
        $this->totalExpenditureRepo = m::mock(TotalExpenditure::class);
        $this->orgDataModel         = m::mock(OrganizationData::class);
        $this->version->shouldReceive('getOrganizationElement->getTotalExpenditureRepository')->andReturn($this->totalExpenditureRepo);
        $this->totalExpenditureManager = new TotalExpenditureManager($this->version, $this->auth, $this->database, $this->dbLogger, $this->logger);
    }

    public function testItShouldUpdateTotalExpenditureData()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->once()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->once()->andReturn($user);
        $this->orgDataModel->shouldReceive('getAttribute')->once()->with('total_expenditure')->andReturn([]);
        $this->totalExpenditureRepo->shouldReceive('update')->once()->with([], $this->orgDataModel)->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with("Organization Total Expenditure Updated", ['for' => []]);
        $this->dbLogger->shouldReceive('activity')->once()->with('organization.total_expenditure_updated', ['name' => 'organizationName']);
        $this->assertTrue($this->totalExpenditureManager->update([], $this->orgDataModel));
    }

    public function testItShouldGetAllOrganizationDataWithCertainId()
    {
        $this->totalExpenditureRepo->shouldReceive('getOrganizationData')->with(1)->andReturn($this->orgDataModel);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->totalExpenditureManager->getOrganizationData(1));
    }

    public function testItShouldGetOrganizationTotalExpenditureDataWithCertainId()
    {
        $this->totalExpenditureRepo->shouldReceive('getOrganizationTotalExpenditureData')->with(1)->andReturn($this->orgDataModel);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->totalExpenditureManager->getOrganizationTotalExpenditureData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
