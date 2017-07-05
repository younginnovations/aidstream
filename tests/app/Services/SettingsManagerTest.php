<?php namespace App\Services;

use App\Core\Version;
use App\Core\V201\Repositories\SettingsRepository;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\Services\UserOnBoarding\UserOnBoardingService;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;
use Kris\LaravelFormBuilder\FormBuilder;

class SettingsManagerTest extends AidStreamTestCase
{

    protected $version;
    protected $settingRepo;
    protected $settingManager;
    protected $setting;
    protected $activityManager;
    protected $organizationManager;
    protected $logger;
    protected $dbLogger;
    protected $auth;
    protected $dbManager;
    protected $userOnBoardingService;
    protected $formBuilder;

    public function setUp()
    {
        parent::setUp();
        $this->version     = m::mock(Version::class);
        $this->settingRepo = m::mock(SettingsRepository::class);
        $this->version->shouldReceive('getSettingsElement->getRepository')->andReturn($this->settingRepo);
        $this->activityManager       = m::mock(ActivityManager::class);
        $this->organizationManager   = m::mock(OrganizationManager::class);
        $this->setting               = m::mock(Settings::class);
        $this->logger                = m::mock(LoggerInterface::class);
        $this->dbLogger              = m::mock(Log::class);
        $this->auth                  = m::mock(Guard::class);
        $this->dbManager             = m::mock(DatabaseManager::class);
        $this->formBuilder           = m::mock(FormBuilder::class);
        $this->userOnBoardingService = m::mock(UserOnBoardingService::class);
        $this->settingManager        = new SettingsManager(
            $this->version,
            $this->activityManager,
            $this->organizationManager,
            $this->dbManager,
            $this->auth,
            $this->dbLogger,
            $this->logger,
            $this->formBuilder,
            $this->userOnBoardingService
        );
    }

    /**
     * @test
     */
    public function testItShouldReturnSettingsDataWithSpecificOrganizationId()
    {
        $this->settingRepo->shouldReceive('getSettings')->once()->with(1)->andReturn([]);
        $this->assertTrue(is_array($this->settingManager->getSettings(1)));
    }

    /**
     * @test
     */
    public function testItShouldStoreSetting()
    {
        $this->settingRepo->shouldReceive('storeSettings')->once()->with('testSetting', 1)->andReturn(true);
        $this->assertTrue($this->settingManager->storeSettings('testSetting', 1));
    }

    /**
     * @test
     */
    public function testItShouldUpdateSetting()
    {
        $this->dbManager->shouldReceive('beginTransaction');
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $this->settingRepo->shouldReceive('updateSettings')->once()->with('testSetting', 1, 'settingsData')->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with('Settings Updated Successfully.');
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.settings_updated',
            [
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->dbManager->shouldReceive('commit');
        $this->assertTrue($this->settingManager->updateSettings('testSetting', 1, 'settingsData'));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
