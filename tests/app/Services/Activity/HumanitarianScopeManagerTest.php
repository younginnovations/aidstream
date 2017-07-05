<?php namespace Test\app\Services\Activity;

use App\Core\V202\Repositories\Activity\HumanitarianScope;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\HumanitarianScopeManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class HumanitarianScopeManagerTest
 * @package Test\app\Services\Activity
 */
class HumanitarianScopeManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $logger;
    protected $activity;
    protected $database;
    protected $humanitarianScopeRepo;
    protected $dbLogger;
    protected $humanitarianScopeManager;

    public function SetUp()
    {
        parent::setUp();
        $this->version               = m::mock(Version::class);
        $this->auth                  = m::mock(Guard::class);
        $this->logger                = m::mock(Logger::class);
        $this->dbLogger              = m::mock(DbLogger::class);
        $this->database              = m::mock(DatabaseManager::class);
        $this->humanitarianScopeRepo = m::mock(HumanitarianScope::class);
        $this->activity              = m::mock(Activity::class);
        $this->version->shouldReceive('getActivityElement->getHumanitarianScopeRepository')->andReturn(
            $this->humanitarianScopeRepo
        );
        $this->humanitarianScopeManager = new HumanitarianScopeManager($this->version, $this->auth, $this->database, $this->dbLogger, $this->logger);
    }

    /**
     * @test
     */
    public function testItShouldGetActivityDataWithCertainActivityId()
    {
        $this->humanitarianScopeRepo->shouldReceive('getActivityData')->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->humanitarianScopeManager->getActivityData(1));
    }

    /**
     * @test
     */
    public function testItShouldGetHumanitarianScopeDataWithCertainId()
    {
        $this->humanitarianScopeRepo->shouldReceive('getActivityHumanitarianScopeData')->once()->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->humanitarianScopeManager->getActivityHumanitarianScopeData(1));
    }

    /**
     * @test
     */
    public function testItShouldUpdateActivityHumanitarianScope()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->andReturn($user);
        $this->activity->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->activity->shouldReceive('getAttribute')->once()->with('humanitarian_scope')->andReturn('testHumanitarianScope');
        $this->database->shouldReceive('beginTransaction');
        $this->humanitarianScopeRepo->shouldReceive('update')->once()->with(['humanitarian_scope' => 'testHumanitarianScope'], $this->activity)->andReturn(true);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('info')->once()->with('Activity Humanitarian Scope Updated', ['for' => 'testHumanitarianScope']);
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.humanitarian_scope_updated', ['activity_id' => 1, 'organization' => 'orgName', 'organization_id' => 1]);
        $this->assertTrue($this->humanitarianScopeManager->update(['humanitarian_scope' => 'testHumanitarianScope'], $this->activity));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
