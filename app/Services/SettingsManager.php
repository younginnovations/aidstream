<?php namespace App\Services;

use App\Core\Version;
use App;
use App\Models\Activity\Activity;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;

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
     * @param Version             $version
     * @param ActivityManager     $activityManager
     * @param OrganizationManager $organizationManager
     */
    function __construct(Version $version, ActivityManager $activityManager, OrganizationManager $organizationManager)
    {
        $this->repo                = $version->getSettingsElement()->getRepository();
        $this->activityManager     = $activityManager;
        $this->organizationManager = $organizationManager;
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
     */
    public function updateSettings($input, $organization, $settings)
    {
        $this->repo->updateSettings($input, $organization, $settings);
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
