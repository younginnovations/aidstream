<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\DefaultTiedStatus;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\DefaultTiedStatusManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultTiedStatusManagerTest
 * @package Test\app\Services\Activity
 */
class DefaultTiedStatusManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $defaultTiedStatusRepo;
    protected $defaultTiedStatusManager;
    protected $activity;
    protected $database;

    public function setUp()
    {
        parent::setUp();
        $this->version               = m::mock(Version::class);
        $this->auth                  = m::mock(Guard::class);
        $this->dbLogger              = m::mock(DbLogger::class);
        $this->logger                = m::mock(Logger::class);
        $this->defaultTiedStatusRepo = m::mock(DefaultTiedStatus::class);
        $this->activity              = m::mock(Activity::class);
        $this->database              = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getDefaultTiedStatus->getRepository')->andReturn(
            $this->defaultTiedStatusRepo
        );
        $this->defaultTiedStatusManager = new DefaultTiedStatusManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function testItShouldUpdateActivityDefaultTiedStatus()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('default_tied_status')->andReturn(
            'testDefaultTiedStatus'
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->defaultTiedStatusRepo->shouldReceive('update')
                                    ->once()
                                    ->with(['default_tied_status' => 'testDefaultTiedStatus'], $activityModel)
                                    ->andReturn(true);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Default Tied Status updated!',
            ['for' => 'testDefaultTiedStatus']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.default_tied_status',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->defaultTiedStatusManager->update(
                ['default_tied_status' => 'testDefaultTiedStatus'],
                $activityModel
            )
        );
    }

    /**
     * @test
     */
    public function testItShouldGetDefaultTiedStatusDataWithCertainId()
    {
        $this->defaultTiedStatusRepo->shouldReceive('getDefaultTiedStatusData')->once()->with(1)->andReturn(
            $this->activity
        );
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->defaultTiedStatusManager->getDefaultTiedStatusData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
