<?php namespace Test\app\Services;

use App\Core\V201\Repositories\Upgrade;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Services\UpgradeManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class UpgradeManagerTest
 * @package Test\app\Services\Activity
 */
class UpgradeManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $upgradeRepo;
    protected $upgradeManager;
    protected $database;

    public function SetUp()
    {
        parent::setUp();
        $this->version     = m::mock(Version::class);
        $this->auth        = m::mock(Guard::class);
        $this->dbLogger    = m::mock(DbLogger::class);
        $this->logger      = m::mock(Logger::class);
        $this->upgradeRepo = m::mock(Upgrade::class);
        $this->database    = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getSettingsElement->getUpgradeRepository')->andReturn(
            $this->upgradeRepo
        );
        $this->upgradeManager = new UpgradeManager(
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
    public function testItShouldUpgradeToCertainVersionWithCertainOrganizationId()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->twice()->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->times(3)->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->times(3)->andReturn($user);
        $this->database->shouldReceive('beginTransaction');
        $this->upgradeRepo->shouldReceive('upgrade')->once()->with(1, 'version')->andReturn(true);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('info')->once()->with(
            sprintf('Version Upgraded to %s for Organization %s!', 'version', 'orgName'),
            ['for' => 1]
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.version_upgraded',
            [
                'organization'    => 'orgName',
                'organization_id' => 1,
                'version'         => 'version'
            ]
        );
        $this->assertTrue($this->upgradeManager->upgrade(1, 'version'));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
