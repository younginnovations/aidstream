<?php namespace App\Http\Controllers;

use App\Services\Activity\ChangeActivityDefaultManager;
use App\Services\Activity\ResultManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\ChangeActivityDefault;
use App\Services\Organization\OrganizationManager;

use App\Services\SettingsManager;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Identifier;
use App\User;
use Psr\Log\LoggerInterface;


class CompleteValidateController extends Controller
{

    /**
     * @var ChangeActivityDefault
     */
    protected $changeActivityDefaultForm;

    /**
     * @var ChangeActivityDefaultManager
     */
    protected $changeActivityDefaultManager;
    /**
     * @var SettingsManager
     */
    private $settingsManager;
    /**
     * @var SessionManager
     */
    private $sessionManager;
    /**
     * @var OrganizationManager
     */
    private $organizationManager;
    /**
     * @var ActivityManager
     */
    private $activityManager;

    /**
     * @param SettingsManager     $settingsManager
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     * @param ActivityManager     $activityManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->settingsManager     = $settingsManager;
        $this->sessionManager      = $sessionManager;
        $this->organizationManager = $organizationManager;
        $this->activityManager     = $activityManager;
    }

    public function show($activityId)
    {
        $activityData    = $this->activityManager->getActivityData($activityId);
        $settings        = $this->settingsManager->getSettings($activityData['organization_id']);
        $transactionData = $this->activityManager->getTransactionData($activityId);
        $resultData      = $this->activityManager->getResultData($activityId);
        $organization    = $this->organizationManager->getOrganization($activityData->organization_id);
        $orgElem         = $this->organizationManager->getOrganizationElement();
        $activityElement = $this->activityManager->getActivityElement();

        return $this->validateCompletedActivity($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
    }

    public function validateCompletedActivity($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization)
    {
        $xmlService     = $activityElement->getActivityXmlService();
        $tempXmlContent = $xmlService->generateTemporaryActivityXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);

        $messages = $xmlService->validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);

        $messages !== '' ?: $messages = [];

        $tempXmlContent = htmlspecialchars($tempXmlContent);
        return view('validate-schema', compact('messages', 'tempXmlContent'));
    }
}