<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Requests\Activity\IatiIdentifierRequest;
use App\Http\Controllers\Controller;
use App\Services\Activity\ChangeActivityDefaultManager;
use App\Services\Activity\ResultManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\ChangeActivityDefault;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\ChangeActivityDefault as ChangeActivityDefaultRequest;
use App\Services\RequestManager\ActivityElementValidator;
use App\Services\SettingsManager;
use App\Http\Requests\Request;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Identifier;
use Illuminate\Support\Facades\Session;
use App\Http\API\CKAN\CkanClient;
use App\User;
use Psr\Log\LoggerInterface;

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
     * @param User                         $user
     * @param LoggerInterface              $loggerInterface
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
        ChangeActivityDefaultManager $changeActivityDefaultManager,
        User $user,
        LoggerInterface $loggerInterface
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
        $this->user                         = $user;
        $this->loggerInterface              = $loggerInterface;
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
     * @param bool $duplicate
     * @param int  $activityId
     * @return \Illuminate\View\View
     */
    public function create($duplicate = false, $activityId = 0)
    {
        $this->authorize('add_activity');
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        $form         = $duplicate ? $this->identifierForm->duplicate($activityId) : $this->identifierForm->create();
        $settings     = $this->settingsManager->getSettings($this->organization_id);

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $defaultFieldValues    = $settings->default_field_values;
        $reportingOrganization = $organization->reporting_org;

        return view('Activity.create', compact('form', 'organization', 'reportingOrganization', 'defaultFieldValues', 'duplicate'));
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
     * @param                          $id
     * @param Request                  $request
     * @param ActivityElementValidator $activityElementValidator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request, ActivityElementValidator $activityElementValidator)
    {
        $input            = $request->all();
        $activityData     = $this->activityManager->getActivityData($id);
        $settings         = $this->settingsManager->getSettings($activityData['organization_id']);
        $activityWorkflow = $input['activity_workflow'];
        $transactionData  = $this->activityManager->getTransactionData($id);
        $resultData       = $this->activityManager->getResultData($id);
        $organization     = $this->organizationManager->getOrganization($activityData->organization_id);
        $orgElem          = $this->organizationManager->getOrganizationElement();
        $activityElement  = $this->activityManager->getActivityElement();
        $xmlService       = $activityElement->getActivityXmlService();

        if ($activityWorkflow == 1) {
            $validationMessage = $activityElementValidator->validateActivity($activityData, $transactionData);
            if($validationMessage){
                $response = ['type' => 'warning', 'code' => ['message', ['message' => $validationMessage]]];

                return redirect()->back()->withResponse($response);
            }

            $messages = $xmlService->validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
            if ($messages) {
                $response = ['type' => 'danger', 'messages' => $messages];

                return redirect()->back()->withResponse($response);
            }
        } elseif ($activityWorkflow == 3) {
            if (empty($settings['registry_info'][0]['publisher_id']) && empty($settings['registry_info'][0]['api_id'])) {
                $response = ['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]];

                return redirect()->to('/settings')->withResponse($response);
            }
            $xmlService->generateActivityXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
            $publishedStatus = $this->publishToRegistry();

            if (!$publishedStatus) {
                $this->activityManager->updateStatus($input, $activityData);
                $this->activityManager->makePublished($activityData);
                $response = ['type' => 'warning', 'code' => ['publish_registry', ['name' => '']]];

                return redirect()->back()->withResponse($response);
            }
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
        $defaultFieldValues         = ($activityData->default_field_values[0]) ? $activityData->default_field_values[0] : $SettingsDefaultFieldValues[0];
        $defaultFieldValues         = [array_merge($defaultFieldValues, $request->except(['_method', '_token']))];
        $result                     = $this->changeActivityDefaultManager->update($defaultFieldValues, $activityData);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['save_failed', ['name' => 'Activity Defaults']]];

            return redirect()->back()->withResponse($response);
        }
        $this->activityManager->resetActivityWorkflow($activityId);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Defaults']]];

        return redirect()->route('activity.show', [$activityId])->withResponse($response);
    }

    /**
     * @return bool
     */
    public function publishToRegistry()
    {
        $activityPublishedFiles = $this->activityManager->getActivityPublishedFiles($this->organization_id);
        $settings               = $this->settingsManager->getSettings($this->organization_id);
        $api_url                = 'http://iati2.staging.ckanhosted.com/api/';
        $apiCall                = new CkanClient($api_url, $settings['registry_info'][0]['api_id']);

        try {
            foreach ($activityPublishedFiles as $publishedFile) {
                $data = $this->generateJson($publishedFile);
                if ($settings->publishing_type == "segmented") {
                    $filename = explode('-', $publishedFile->filename);
                    $code     = str_replace('.xml', '', end($filename));
                }
                if ($publishedFile['published_to_register'] == 0) {
                    $apiCall->post_package_register($data);
                    $this->activityManager->updatePublishToRegister($publishedFile->id);
                } elseif ($publishedFile['published_to_register'] == 1) {
                    $package = ($settings->publishing_type == "segmented") ? $settings['registry_info'][0]['publisher_id'] . '-' . $code : $settings['registry_info'][0]['publisher_id'] . '-activities';
                    $apiCall->put_package_entity($package, $data);
                }
            }

            return true;
        } catch (\Exception $e) {
            $this->loggerInterface->error(sprintf('Registry Info could not be registered due to %s', $e->getMessage()));

            return false;
        }
    }

    /**
     * @param $publishedFile
     * @return string
     */
    public function generateJson($publishedFile)
    {
        $settings     = $this->settingsManager->getSettings($this->organization_id);
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        $email        = $this->user->getUserByOrgId();
        $author_email = $email[0]->email;
        $code         = "";

        if ($settings->publishing_type == "segmented") {
            $filename = explode('-', $publishedFile->filename);
            $code     = str_replace('.xml', '', end($filename));
        }

        if ($code == "998") {
            $key = "Others";
        } elseif (is_numeric($code)) {
            $key = "region";
        } else {
            $key = "country";
        }

        $title = ($settings->publishing_type == "segmented") ? $organization->name . 'Activity File-' . $code : $organization->name . 'Activity File';
        $name  = ($settings->publishing_type == "segmented") ? $settings['registry_info'][0]['publisher_id'] . '-' . $code : $settings['registry_info'][0]['publisher_id'] . '-activities';

        $data = '{
        "title": "' . $title . '",
        "name": "' . $name . '",
        "author_email": "' . $author_email . '",
        "owner_org" : "' . $settings['registry_info'][0]['publisher_id'] . '",
        "resources": [
        {
            "format":"IATI-XML",
            "mimetype":"application/xml",
            "url":"' . url('uploads/files/activity/' . $publishedFile->filename) . '"
        }
        ],
        "extras":
        [
        {"key":"filetype","value":"activity"},
        {"key":"' . $key . '","value":"' . $code . '"},
        {"key":"data_updated","value":"' . $publishedFile->updated_at . '"},
        {"key":"activity_count", "value":"' . count($publishedFile->published_activities) . '"},
        {"key":"language","value":"' . config('app.locale') . '"},
        {"key":"verified","value":"no"}]}';

        return $data;
    }

    /**
     * show identifier form to duplicate activity
     * @param $activityId
     * @return \Illuminate\View\View
     */
    public function duplicateActivity($activityId)
    {
        return $this->create(true, $activityId);
    }

    /**
     * duplicate activity
     * @param                       $activityId
     * @param IatiIdentifierRequest $request
     * @return mixed
     */
    public function duplicateActivityAction($activityId, IatiIdentifierRequest $request)
    {
        $activityData                   = $this->activityManager->getActivityData($activityId);
        $newItem                        = $activityData->replicate();
        $input                          = $request->all();
        $newItem->identifier            = ["activity_identifier" => $input['activity_identifier'], "iati_identifier_text" => $input['iati_identifier_text']];
        $newItem->activity_workflow     = 0;
        $newItem->published_to_registry = 0;
        $newItem->save();
        $response = ['type' => 'success', 'code' => ['duplicated', ['url' => route('activity.show', [$newItem->id])]]];

        return redirect('/activity')->withResponse($response);
    }
}
