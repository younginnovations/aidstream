<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\LegacyData;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\LegacyDataManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class LegacyDataManagerTest
 * @package Test\app\Services\Activity
 */
class LegacyDataManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $version;
    protected $legacyDataRepository;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $legacyDataManager;

    public function setup()
    {
        parent::setup();
        $this->activity             = m::mock(Activity::class);
        $this->version              = m::mock(Version::class);
        $this->legacyDataRepository = m::mock(LegacyData::class);
        $this->logger               = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getLegacyData->getRepository')->andReturn($this->legacyDataRepository);
        $this->dbLogger          = m::mock(Log::class);
        $this->auth              = m::mock(Guard::class);
        $this->legacyDataManager = new LegacyDataManager($this->version, $this->dbLogger, $this->auth, $this->logger);
    }

    /**
     * @test
     */
    public function testItShouldUpdateLegacyData()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('legacy_data')->andReturn('legacyData');
        $this->legacyDataRepository->shouldReceive('update')->once()->with(['legacy_data' => 'legacyData'], $activityModel)->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with('Legacy Data Updated!', ['for' => 'legacyData']);
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.legacy_data_updated', ['activity_id' => 1, 'organization' => 'organizationName', 'organization_id' => 1]);
        $this->assertTrue($this->legacyDataManager->update(['legacy_data' => 'legacyData'], $activityModel));
    }

    /**
     * @test
     */
    public function testItShouldGetLegacyDataWithCertainId()
    {
        $this->legacyDataRepository->shouldReceive('getLegacyData')->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->legacyDataManager->getLegacyData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
