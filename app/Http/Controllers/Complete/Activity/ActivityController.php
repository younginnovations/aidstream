<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Requests\Activity\IatiIdentifierRequest;
use App\Http\Controllers\Controller;
use App\Services\Activity\ChangeActivityDefaultManager;
use App\Services\Activity\ResultManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\ChangeActivityDefault;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\ChangeActivityDefault as ChangeActivityDefaultRequest;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Identifier;
use Illuminate\Support\Facades\Session;
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
    protected $transactionManager;
    /**
     * @var ChangeActivityDefault
     */
    private $changeActivityDefaultForm;
    /**
     * @var ChangeActivityDefaultManager
     */
    protected $changeActivityDefaultManager;

    /**
     * @param SettingsManager              $settingsManager
     * @param SessionManager               $sessionManager
     * @param OrganizationManager          $organizationManager
     * @param Identifier                   $identifierForm
     * @param ActivityManager              $activityManager
     * @param ResultManager                $resultManager
     * @param TransactionManager           $transactionManager
     * @param ChangeActivityDefault        $changeActivityDefaultForm
     * @param ChangeActivityDefaultManager $changeActivityDefaultManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        Identifier $identifierForm,
        ActivityManager $activityManager,
        ResultManager $resultManager,
        TransactionManager $transactionManager,
        ChangeActivityDefault $changeActivityDefaultForm,
        ChangeActivityDefaultManager $changeActivityDefaultManager
    ) {
        $this->middleware('auth');
        $this->settingsManager              = $settingsManager;
        $this->sessionManager               = $sessionManager;
        $this->organizationManager          = $organizationManager;
        $this->identifierForm               = $identifierForm;
        $this->activityManager              = $activityManager;
        $this->organization_id              = $this->sessionManager->get('org_id');
        $this->resultManager                = $resultManager;
        $this->transactionManager           = $transactionManager;
        $this->changeActivityDefaultForm    = $changeActivityDefaultForm;
        $this->changeActivityDefaultManager = $changeActivityDefaultManager;
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
        $organization = $this->organizationManager->getOrganization(Session::get('org_id'));
        $form         = $this->identifierForm->create();
        $settings     = $this->settingsManager->getSettings($this->organization_id);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
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
        $settings           = $this->settingsManager->getSettings($this->organization_id);
        $defaultFieldValues = $settings->default_field_values;
        $input              = $request->all();
        $result             = $this->activityManager->store($input, $this->organization_id, $defaultFieldValues);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['save_failed', ['name' => 'activity']]];

            return redirect()->back()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['created', ['name' => 'Activity']]];

        return redirect()->route('activity.show', [$result->id])->withResponse($response);
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
                $response = ['type' => 'danger', 'code' => ['transfer_message', ['name' => $message]]];

                return redirect()->back()->withResponse($response);
            }
        } elseif ($activityWorkflow == 3) {
            $xmlService->generateActivityXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
        }
        $statusLabel = ['Completed', 'Verified', 'Published'];
        $response    = ($this->activityManager->updateStatus($input, $activityData)) ?
            ['type' => 'success', 'code' => ['activity_statuses', ['name' => $statusLabel[$activityWorkflow - 1]]]] :
            ['type' => 'danger', 'code' => ['activity_statuses_failed', ['name' => $statusLabel[$activityWorkflow - 1]]]];

        return redirect()->back()->withResponse($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $activity = $this->activityManager->getActivityData($id);
        $response = ($activity->delete($activity)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Activity']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'activity']]
        ];

        return redirect()->back()->withResponse($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        $result   = $this->activityManager->deletePublishedFile($id);
        $message  = $result ? 'File deleted successfully' : 'File couldn\'t be deleted.';
        $type     = $result ? 'success' : 'danger';
        $response = ['type' => $type, 'code' => ['transfer_message', ['name' => $message]]];

        return redirect()->back()->withResponse($response);
    }

    /**
     * show form to update activity default values
     * @param $activityId
     * @return \Illuminate\View\View
     */
    public function changeActivityDefault($activityId)
    {
        $activityData       = $this->activityManager->getActivityData($activityId);
        $defaultFieldValues = $activityData->default_field_values;
        $form               = $this->changeActivityDefaultForm->edit($defaultFieldValues, $activityId);

        return view('Activity.changeActivityDefault', compact('form', 'defaultFieldValues', 'activityId'));
    }

    /**
     * Update Activity default values
     * @param                              $activityId
     * @param Request                      $request
     * @param ChangeActivityDefaultRequest $changeActivityDefaultRequest
     * @return mixed
     */
    public function updateActivityDefault($activityId, Request $request, ChangeActivityDefaultRequest $changeActivityDefaultRequest)
    {
        $activityData               = $this->activityManager->getActivityData($activityId);
        $settings                   = $this->settingsManager->getSettings($this->organization_id);
        $SettingsDefaultFieldValues = $settings->default_field_values;
        $defaultFieldValues         = ($activityData->default_field_values[0]) ? $activityData->default_field_values[0] : $SettingsDefaultFieldValues;
        $defaultFieldValues         = [array_merge($defaultFieldValues, $request->except(['_method', '_token']))];
        $result                     = $this->changeActivityDefaultManager->update($defaultFieldValues, $activityData);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['save_failed', ['name' => 'Activity Defaults']]];

            return redirect()->back()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Defaults']]];

        return redirect()->route('activity.show', [$activityId])->withResponse($response);
    }
}
