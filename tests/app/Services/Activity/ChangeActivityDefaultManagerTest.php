<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\ChangeActivityDefault;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\Services\Activity\ChangeActivityDefaultManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ChangeActivityDefaultManagerTest
 * @package Test\app\Services\Activity
 */
class ChangeActivityDefaultManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $activity;
    protected $database;
    protected $changeActivityDefaultRepo;
    protected $changeActivityDefaultManager;
    protected $settings;

    public function SetUp()
    {
        parent::setUp();
        $this->version                   = m::mock(Version::class);
        $this->auth                      = m::mock(Guard::class);
        $this->dbLogger                  = m::mock(DbLogger::class);
        $this->logger                    = m::mock(Logger::class);
        $this->settings                  = m::mock(Settings::class);
        $this->changeActivityDefaultRepo = m::mock(ChangeActivityDefault::class);
        $this->database                  = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getChangeActivityDefault->getRepository')->andReturn($this->changeActivityDefaultRepo);
        $this->changeActivityDefaultManager = new ChangeActivityDefaultManager($this->version, $this->auth, $this->database, $this->dbLogger, $this->logger);
    }

    public function testItShouldUpdateActivityDefaultValues()
    {
        $orgModel = m::mock(Organization::class);
        $user     = m::mock(User::class);
        $user->shouldReceive('getAttribute')->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->andReturn($user);
        $orgModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->changeActivityDefaultRepo->shouldReceive('update')->once()->with([], $this->settings)->andReturn(true);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->logger->shouldReceive('info')->once()->with('Activity Default Values updated!', ['for' => []]);
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.activity_default_values', ['organization_id' => 1]);
        $this->assertTrue($this->changeActivityDefaultManager->update([], $this->settings));
    }

    public function testItShouldGetActivityDefaultValuesWithCertainOrgId()
    {
        $this->changeActivityDefaultRepo->shouldReceive('getActivityDefaultValues')->once()->with(1)->andReturn($this->settings);
        $this->assertInstanceOf('App\Models\Settings', $this->changeActivityDefaultManager->getActivityDefaultValues(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
