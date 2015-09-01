<?php
namespace App\Core\V201;

use App;

class IatiSettings
{

    public function getVersionInfo()
    {
        return App::make('App\Core\V201\Element\Settings\VersionInfo');
    }

    public function getReportingOrganizationInfo()
    {
        return App::make('App\Core\V201\Element\Organization\ReportingOrganizationInfo');
    }

    public function getPublishingType()
    {
        return App::make('App\Core\V201\Element\Settings\PublishingType');
    }

    public function getRegistryInfo()
    {
        return App::make('App\Core\V201\Element\Settings\RegistryInfo');
    }

    public function getDefaultFieldValues()
    {
        return App::make('App\Core\V201\Element\Settings\DefaultFieldValues');
    }

    public function getDefaultFieldGroups()
    {
        return App::make('App\Core\V201\Element\Settings\DefaultFieldGroups');
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\SettingsRepository');
    }
}
?>