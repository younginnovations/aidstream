<?php namespace App\Services;

use App\Core\Version;
use App;
use App\Http\API\CKAN\CkanClient;
use App\Models\Activity\Activity;
use App\Models\Settings;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Auth\Guard;
use Kris\LaravelFormBuilder\FormBuilder;
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

    protected $formBuilder;

    protected $formPath;

    protected $version;

    protected $ckanClient;

    /**
     * @param Version             $version
     * @param ActivityManager     $activityManager
     * @param OrganizationManager $organizationManager
     * @param DatabaseManager     $dbManager
     * @param Guard               $auth
     * @param Log                 $dbLogger
     * @param LoggerInterface     $logger
     * @param FormBuilder         $formBuilder
     */
    function __construct(
        Version $version,
        ActivityManager $activityManager,
        OrganizationManager $organizationManager,
        DatabaseManager $dbManager,
        Guard $auth,
        Log $dbLogger,
        LoggerInterface $logger,
        FormBuilder $formBuilder
    ) {
        $this->repo                = $version->getSettingsElement()->getRepository();
        $this->activityManager     = $activityManager;
        $this->organizationManager = $organizationManager;
        $this->dbLogger            = $dbLogger;
        $this->logger              = $logger;
        $this->dbManager           = $dbManager;
        $this->auth                = $auth;
        $this->formBuilder         = $formBuilder;
        $this->version             = $version;
        $this->formPath            = $this->version->getSettingsElement();

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
     * return settings
     * @param $code
     * @return mixed
     */
    public function getSettingsByCode($code)
    {
        return $this->repo->getSettingsByCode($code);
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

    /**
     * Display form to view publishing information.
     * @param $formOptions
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function viewPublishingInfo($formOptions)
    {
        return $this->formBuilder->create($this->formPath->getPublishingInfo(), $formOptions);

    }

    /**
     * save publishing information.
     * @param $publishing_info
     * @param $settings
     * @return bool
     */
    public function savePublishingInfo($publishing_info, $settings)
    {
        try {
            $result = $this->repo->savePublishingInfo($publishing_info, $settings);
            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.publishing_settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $e) {
            $this->logger->error($e, ['settings' => $publishing_info]);
        }

        return false;
    }

    /**
     * display form to view default field values.
     * @param $formOptions
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function viewDefaultValues($formOptions)
    {
        return $this->formBuilder->create($this->formPath->getDefaultValues(), $formOptions);
    }

    /**
     * save default field values.
     * @param $default_values
     * @param $settings
     * @return bool
     */
    public function saveDefaultValues($default_values, $settings)
    {
        try {
            $result = $this->repo->saveDefaultValues($default_values, $settings);
            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.default_values_settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $e) {
            $this->logger->error($e, ['settings' => $default_values]);
        }

        return false;
    }

    /**
     * display form to view activity elements checklist.
     * @param $formOptions
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function viewActivityElementsChecklist($formOptions)
    {
        return $this->formBuilder->create($this->formPath->getActivityElementsChecklist(), $formOptions);
    }

    /**
     * save activity elements checklist.
     * @param $default_field_groups
     * @param $settings
     * @return bool
     */
    public function saveActivityElementsChecklist($default_field_groups, $settings)
    {
        try {
            $this->repo->saveActivityElementsChecklist($default_field_groups, $settings);
            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.activity_elements_checklist_settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $e) {
            $this->logger->error($e, ['settings' => $default_field_groups]);
        }

        return false;
    }

    /**
     * Verify publisher id of organization
     * @param $publisherId
     * @return bool
     */
    public function verifyPublisherId($publisherId)
    {
        try {
            $api_url = config('filesystems.iati_registry_api_base_url');

            if ($publisherId != "") {
                $apiCall  = new CkanClient($api_url);
                $response = json_decode($apiCall->organization_show($publisherId), true);

                return true;
            }

        } catch (Exception $exception) {

            return false;
        }

    }

    /**
     * Verify api key of organization
     * @param $apiKey
     * @return mixed
     */
    public function verifyApiKey($apiKey)
    {
        $uri      = "http://iatiregistry.org/api/3/action/dashboard_activity_list";
        $response = json_decode(shell_exec("curl -H 'Authorization:$apiKey' $uri"), true);
        $status   = $response['success'];

        return $status;
    }
}
