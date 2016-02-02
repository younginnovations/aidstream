<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Settings;
use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\Settings as SettingsModel;

/**
 * Class SettingsMigrator
 * @package App\Migration\Migrator
 */
class SettingsMigrator implements MigratorContract
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var SettingsModel
     */
    protected $settingsModel;

    /**
     * SettingsMigrator constructor.
     * @param Settings      $settings
     * @param SettingsModel $settingsModel
     */
    public function __construct(Settings $settings, SettingsModel $settingsModel)
    {
        $this->settings      = $settings;
        $this->settingsModel = $settingsModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate()
    {
        $settingsData = $this->settings->getData();

        foreach ($settingsData as $setting) {
            $newSettings = $this->settingsModel->newInstance($setting);

            if (!$newSettings->save()) {
                return 'Error during Settings table migration.';
            }
        }

        return 'Settings table migrated';
    }
}
