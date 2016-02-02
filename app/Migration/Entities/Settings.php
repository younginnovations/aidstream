<?php namespace App\Migration\Entities;


use App\Migration\MigrateSettings;

/**
 * Class Settings
 * @package App\Migration\Entities
 */
class Settings
{
    /**
     * @var MigrateSettings
     */
    protected $settings;

    /**
     * Settings constructor.
     * @param MigrateSettings $settings
     */
    public function __construct(MigrateSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $orgIds       = ['2', '100', '9']; // fetch OrgIds.
        $SettingsData = [];

        foreach ($orgIds as $id) {
            $SettingsData[] = $this->settings->SettingsDataFetch($id);
        }

        return $SettingsData;
    }
}
