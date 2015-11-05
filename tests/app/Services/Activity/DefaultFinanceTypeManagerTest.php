<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\DefaultFinanceType;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\DefaultFinanceTypeManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultFinanceTypeManagerTest
 * @package Test\app\Services\Activity
 */
class DefaultFinanceTypeManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $defaultFinanceTypeRepo;
    protected $defaultFinanceTypeManager;
    protected $activity;
    protected $database;

    public function SetUp()
    {
        parent::setUp();
        $this->version                = m::mock(Version::class);
        $this->auth                   = m::mock(Guard::class);
        $this->dbLogger               = m::mock(DbLogger::class);
        $this->logger                 = m::mock(Logger::class);
        $this->defaultFinanceTypeRepo = m::mock(DefaultFinanceType::class);
        $this->activity               = m::mock(Activity::class);
        $this->database               = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getDefaultFinanceType->getRepository')->andReturn(
            $this->defaultFinanceTypeRepo
        );
        $this->defaultFinanceTypeManager = new DefaultFinanceTypeManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    public function testItShouldUpdateActivityDefaultFinanceType()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('default_finance_type')->andReturn(
            'testDefaultFinanceType'
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->defaultFinanceTypeRepo->shouldReceive('update')
                                     ->once()
                                     ->with(['default_finance_type' => 'testDefaultFinanceType'], $activityModel)
                                     ->andReturn(true);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Default Finance Type updated!',
            ['for' => 'testDefaultFinanceType']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.default_finance_type',
            [
                'default_finance_type' => 'testDefaultFinanceType',
                'organization'         => 'orgName',
                'organization_id'      => 1
            ]
        );
        $this->assertTrue(
            $this->defaultFinanceTypeManager->update(
                ['default_finance_type' => 'testDefaultFinanceType'],
                $activityModel
            )
        );
    }

    public function testItShouldGetDefaultFinanceTypeDataWithCertainId()
    {
        $this->defaultFinanceTypeRepo->shouldReceive('getDefaultFinanceTypeData')->once()->with(1)->andReturn(
            $this->activity
        );
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->defaultFinanceTypeManager->getDefaultFinanceTypeData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
