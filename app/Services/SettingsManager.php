<?php namespace App\Services;

use App\Core\Version;
use App;
use App\Models\Activity\Activity;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;

/**
 * Class SettingsManager
 * @package App\Services
 */
class SettingsManager
{

    /**
     * settings repository
     */
    protected $repo;
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var DatabaseManager
     */
    protected $dbManager;
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @param Version             $version
     * @param ActivityManager     $activityManager
     * @param OrganizationManager $organizationManager
     * @param DatabaseManager     $dbManager
     * @param Guard               $auth
     * @param Log                 $dbLogger
     * @param LoggerInterface     $logger
     */
    function __construct(Version $version, ActivityManager $activityManager, OrganizationManager $organizationManager, DatabaseManager $dbManager, Guard $auth, Log $dbLogger, LoggerInterface $logger)
    {
        $this->repo                = $version->getSettingsElement()->getRepository();
        $this->activityManager     = $activityManager;
        $this->organizationManager = $organizationManager;
        $this->dbLogger            = $dbLogger;
        $this->logger              = $logger;
        $this->dbManager           = $dbManager;
        $this->auth                = $auth;
    }

    /**
     * return settings
     * @param $id
     * @return mixed
     */
    public function getSettings($id)
    {
        return $this->repo->getSettings($id);
    }

    /**
     * store settings
     * @param $input
     * @param $organization
     * @return mixed
     */
    public function storeSettings($input, $organization)
    {
        return $this->repo->storeSettings($input, $organization);
    }

    /**
     * update settings
     * @param $input
     * @param $organization
     * @param $settings
     * @return bool
     */
    public function updateSettings($input, $organization, $settings)
    {
        try {
            $this->dbManager->beginTransaction();
            $this->repo->updateSettings($input, $organization, $settings);
            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );
            $this->dbManager->commit();

            return true;
        } catch (Exception $e) {
            $this->dbManager->rollback();
            $this->logger->error($e, ['settings' => $input]);
        }

        return false;
    }

    /**
     * generate activity xml
     * @param Activity $activity
     */
    public function generateXml(Activity $activity)
    {
        $activity_id     = $activity->id;
        $org_id          = $activity->organization_id;
        $settings        = $this->getSettings($org_id);
        $transactionData = $this->activityManager->getTransactionData($activity_id);
        $resultData      = $this->activityManager->getResultData($activity_id);
        $organization    = $this->organizationManager->getOrganization($org_id);
        $orgElem         = $this->organizationManager->getOrganizationElement();
        $activityElement = $this->activityManager->getActivityElement();
        $xmlService      = $activityElement->getActivityXmlService();
        $xmlService->generateActivityXml($activity, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
    }
}
