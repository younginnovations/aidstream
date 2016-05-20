<?php namespace App\Tz\Aidstream\Services\Setting;

use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgReportingOrgManager;
use App\Tz\Aidstream\Repositories\Setting\SettingRepositoryInterface;
use App\Tz\Aidstream\Traits\SettingsTrait;
use Exception;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

/**
 * Class SettingService
 * @package App\Tz\Aidstream\Services\Setting
 */
class SettingService
{
    use SettingsTrait;

    /**
     * Set default version
     */
    const DEFAULT_VERSION = '2.02';

    /**
     * @var SettingRepositoryInterface
     */
    protected $setting;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OrgReportingOrgManager
     */
    protected $orgReportingOrgManagerManager;

    /**
     * @var OrganizationManager
     */
    protected $orgManager;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * SettingService constructor.
     * @param SettingRepositoryInterface $setting
     * @param LoggerInterface            $logger
     * @param OrgReportingOrgManager     $orgReportingOrgManagerManager
     * @param OrganizationManager        $organizationManager
     * @param DatabaseManager            $databaseManager
     */
    public function __construct(
        SettingRepositoryInterface $setting,
        LoggerInterface $logger,
        OrgReportingOrgManager $orgReportingOrgManagerManager,
        OrganizationManager $organizationManager,
        DatabaseManager $databaseManager
    ) {
        $this->setting                       = $setting;
        $this->logger                        = $logger;
        $this->orgReportingOrgManagerManager = $orgReportingOrgManagerManager;
        $this->orgManager                    = $organizationManager;
        $this->databaseManager               = $databaseManager;
    }

    /**
     * Save Settings
     * @param array $settings
     * @return bool|null
     */
    public function create(array $settings)
    {
        $reportingOrg = $this->formatsReportingOrgData($settings);
        $settings     = $this->formatsFormDataIntoJson($settings, self::DEFAULT_VERSION);

        try {
            $settings['organization_id'] = session('org_id');
            $this->setting->create($settings);
            $organization = $this->orgManager->getOrganization(session('org_id'));
            $this->orgReportingOrgManagerManager->update($reportingOrg, $organization);

            $this->logger->info(
                'Settings successfully created.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Settings could not created due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return null;
        }
    }

    /**
     * Update Settings
     * @param array $settings
     * @param       $id
     * @return bool
     */
    public function update(array $settings, $id)
    {
        try {
            $this->databaseManager->beginTransaction();
            $settingDetails = $this->formatsFormDataIntoJson($settings, self::DEFAULT_VERSION);

            $this->orgReportingOrgManagerManager->update(
                $this->formatsReportingOrgData($settings),
                $this->orgManager->getOrganization(session('org_id'))
            );
            $this->setting->update($settingDetails, $id);

            $this->databaseManager->commit();

            $this->logger->info(
                'Settings updated successfully.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();

            $this->logger->error(
                sprintf('Error while updating Settings due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Find Settings model with help of organization id
     * @param $orgId
     * @return array|mixed
     */
    public function findByOrgId($orgId)
    {
        $settings = $this->setting->findByOrgId($orgId);
        $orgData  = $this->orgManager->getOrganization($orgId);
        $settings = $this->formatsDBDataIntoColumn($settings, $orgData);

        return $settings;
    }
}
