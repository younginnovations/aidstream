<?php namespace App\Core\V201;

class IatiSettings
{
    public function getVersionInfo()
    {
        return app('App\Core\V201\Element\Settings\VersionInfo');
    }

    public function getReportingOrganizationInfo()
    {
        return app('App\Core\V201\Element\Organization\ReportingOrganizationInfo');
    }

    public function getPublishingType()
    {
        return app('App\Core\V201\Element\Settings\PublishingType');
    }

    public function getRegistryInfo()
    {
        return app('App\Core\V201\Element\Settings\RegistryInfo');
    }

    public function getDefaultFieldValues()
    {
        return app('App\Core\V201\Element\Settings\DefaultFieldValues');
    }

    public function getDefaultFieldGroups()
    {
        return app('App\Core\V201\Element\Settings\DefaultFieldGroups');
    }

    public function getRepository()
    {
        return app('App\Core\V201\Repositories\SettingsRepository');
    }

    public function getSettingsRequest()
    {
        return app('App\Core\V201\Requests\SettingsRequest');
    }

    public function getUpgradeRepository()
    {
        return app('App\Core\V201\Repositories\Upgrade');
    }

    public function getDocumentRepository()
    {
        return app('App\Core\V201\Repositories\Document');
    }

    public function getRegisterOrganizationRequest()
    {
        return app('App\Core\V201\Requests\RegisterOrganization');
    }

    public function getRegisterUsersRequest()
    {
        return app('App\Core\V201\Requests\RegisterUsers');
    }

    public function getRegisterRequest()
    {
        return app('App\Core\V201\Requests\Register');
    }

    public function getPasswordRequest()
    {
        return app('App\Core\V201\Requests\Password');
    }

    public function getActivityElementsChecklist()
    {
        return 'App\Core\V201\Forms\Settings\ActivityElementsChecklist';
    }

    public function getDefaultValues()
    {
        return 'App\Core\V201\Forms\Settings\DefaultValues';
    }

    public function getOrganizationInformation()
    {
        return 'App\Core\V201\Forms\Settings\OrganizationInformation';
    }

    public function getPublishingInfo()
    {
        return 'App\Core\V201\Forms\Settings\PublishingInfo';
    }

    public function getRegistrationAgencyRepository()
    {
        return app('App\Core\V201\Repositories\RegistrationAgency');
    }
}
