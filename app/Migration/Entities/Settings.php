<?php namespace App\Migration\Entities;


use App\Migration\MigrateSettings;
use App\Migration\Migrator\Data\SettingsQuery;

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

    protected $settingsQuery;

    /**
     * Settings constructor.
     * @param MigrateSettings $settings
     */
    public function __construct(MigrateSettings $settings, SettingsQuery $settingsQuery)
    {
        $this->settings      = $settings;
        $this->settingsQuery = $settingsQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->settingsQuery->executeFor($accountIds);

//        foreach ($accountIds as $accountId) {
//            if ($organization = getOrganizationFor($accountId)) {
//                $SettingsData[] = $this->settings->SettingsDataFetch($organization->id, $accountId);
//            }
//        }
//
//        return $SettingsData;
    }
}
