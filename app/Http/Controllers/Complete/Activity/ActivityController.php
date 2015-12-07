<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Requests\Activity\IatiIdentifierRequest;
use App\Http\Controllers\Controller;
use App\Services\Activity\ResultManager;
use App\Services\Activity\TransactionManager;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Identifier;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    protected $identifierForm;
    protected $activityManager;
    protected $organization_id;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var ResultManager
     */
    protected $resultManager;
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    /**
     * @param SettingsManager     $settingsManager
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     * @param Identifier          $identifierForm
     * @param ActivityManager     $activityManager
     * @param ResultManager       $resultManager
     * @param TransactionManager  $transactionManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        Identifier $identifierForm,
        ActivityManager $activityManager,
        ResultManager $resultManager,
        TransactionManager $transactionManager
    ) {
        $this->middleware('auth');
        $this->settingsManager     = $settingsManager;
        $this->sessionManager      = $sessionManager;
        $this->organizationManager = $organizationManager;
        $this->identifierForm      = $identifierForm;
        $this->activityManager     = $activityManager;
        $this->organization_id     = $this->sessionManager->get('org_id');
        $this->resultManager       = $resultManager;
        $this->transactionManager  = $transactionManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activities = $this->activityManager->getActivities($this->organization_id);

        return view('Activity.index', compact('activities'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('add_activity');
        $form     = $this->identifierForm->create();
        $settings = $this->settingsManager->getSettings($this->organization_id);
        if (!isset($settings)) {
            return redirect('/settings');
        }
        $defaultFieldValues    = $settings->default_field_values;
        $organization          = $this->organizationManager->getOrganization($this->organization_id);
        $reportingOrganization = $organization->reporting_org;

        return view('Activity.create', compact('form', 'organization', 'reportingOrganization', 'defaultFieldValues'));
    }

    /**
     * store the activity identifier
     * @param IatiIdentifierRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IatiIdentifierRequest $request)
    {
        $input  = $request->all();
        $result = $this->activityManager->store($input, $this->organization_id);
        if (!$result) {
            return redirect()->back();
        }

        return redirect()->route('activity.show', [$result->id]);
    }

    /**
     * show the activity details
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $activityData                    = $this->activityManager->getActivityData($id);
        $activityDataList                = $activityData->activity_data_list;
        $activityResult                  = $this->resultManager->getResults($id)->toArray();
        $activityTransaction             = $this->transactionManager->getTransactions($id)->toArray();
        $activityDataList['results']     = $activityResult;
        $activityDataList['transaction'] = $activityTransaction;

        return view('Activity.show', compact('activityDataList', 'id'));
    }

    /**
     * Throw an unauthorized exception based on gate results.
     *
     * @param  string $ability
     * @param  array  $arguments
     * @return \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function createGateUnauthorizedException($ability, $arguments)
    {
        return new HttpException(403, 'This action is unauthorized.');
    }

    /**
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request)
    {
        $input            = $request->all();
        $activityData     = $this->activityManager->getActivityData($id);
        $settings         = $this->settingsManager->getSettings($activityData['organization_id']);
        $activityWorkflow = $input['activity_workflow'];
        $transactionData  = $this->activityManager->getTransactionData($id);
        $resultData       = $this->activityManager->getResultData($id);
        $organization     = $this->organizationManager->getOrganization($activityData->organization_id);

        $orgElem         = $this->organizationManager->getOrganizationElement();
        $activityElement = $this->activityManager->getActivityElement();
        $xmlService      = $activityElement->getActivityXmlService();

        if ($activityWorkflow == 1) {
            $message = $xmlService->validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
            if ($message !== '') {
                return redirect()->back()->withMessage($message);
            }
        } elseif ($activityWorkflow == 3) {
            $xmlService->generateActivityXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
        }

        $this->activityManager->updateStatus($input, $activityData);

        return redirect()->back();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $activity = $this->activityManager->getActivityData($id);
        $activity->delete($activity);

        return redirect()->back()->withMessage('Activity deleted');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        $result  = $this->activityManager->deletePublishedFile($id);
        $message = $result ? 'File deleted successfully' : 'File couldn\'t be deleted.';

        return redirect()->back()->withMessage($message);
    }
}
