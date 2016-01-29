<?php namespace App\Services;

use App\Core\Version;
use App\Core\V201\Repositories\SettingsRepository;
use App\Models\Settings;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Models\Activity\Activity;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;

class SettingsManagerTest extends AidStreamTestCase
{

    protected $version;
    protected $settingRepo;
    protected $settingManager;
    protected $setting;
    protected $activityManager;
    protected $organizationManager;

    public function SetUp()
    {
        parent::setUp();
        $this->version     = m::mock(Version::class);
        $this->settingRepo = m::mock(SettingsRepository::class);
        $this->version->shouldReceive('getSettingsElement->getRepository')->andReturn($this->settingRepo);
        $this->activityManager     = m::mock(ActivityManager::class);
        $this->organizationManager = m::mock(OrganizationManager::class);
        $this->setting             = m::mock(Settings::class);
        $this->settingManager      = new SettingsManager($this->version, $this->activityManager, $this->organizationManager);
    }

    public function testItShouldReturnSettingsDataWithSpecificOrganizationId()
    {
        $this->settingRepo->shouldReceive('getSettings')->once()->with(1)->andReturn([]);
        $this->assertTrue(is_array($this->settingManager->getSettings(1)));
    }

    public function testItShouldStoreSetting()
    {
        $this->settingRepo->shouldReceive('storeSettings')->once()->with('testSetting', 1)->andReturn(true);
        $this->assertTrue($this->settingManager->storeSettings('testSetting', 1));
    }

    public function testItShouldUpdateSetting()
    {
        $this->settingRepo->shouldReceive('updateSettings')->once()->with('testSetting', 1, 'settingsData')->andReturn(null);
        $this->assertNull($this->settingManager->updateSettings('testSetting', 1, 'settingsData'));
    }

    public function testItShouldGenerateXml()
    {
        $this->settingRepo->shouldReceive('updateSettings')->once()->with('testSetting', 1, 'settingsData')->andReturn(null);
        $this->assertNull($this->settingManager->updateSettings('testSetting', 1, 'settingsData'));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
