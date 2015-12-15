<?php namespace App\Services;

use App\Core\Version;
use App\Core\V201\Repositories\SettingsRepository;
use App\Models\Settings;
use Test\AidStreamTestCase;
use Mockery as m;

class SettingsManagerTest extends AidStreamTestCase
{

    protected $version;
    protected $settingRepo;
    protected $settingManager;
    protected $setting;

    public function SetUp()
    {
        parent::setUp();
        $this->version     = m::mock(Version::class);
        $this->settingRepo = m::mock(SettingsRepository::class);
        $this->version->shouldReceive('getSettingsElement->getRepository')->andReturn($this->settingRepo);
        $this->settingManager = new SettingsManager($this->version);
        $this->setting        = m::mock(Settings::class);
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

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
