<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Requests\Activity\IatiIdentifierRequest;
use App\Http\API\CKAN\CkanClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Models\Settings;
use App\Services\Activity\ChangeActivityDefaultManager;
use App\Services\Activity\DocumentLinkManager;
use App\Services\Activity\ResultManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\ChangeActivityDefault;
use App\Services\FormCreator\Activity\Identifier;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\ChangeActivityDefault as ChangeActivityDefaultRequest;
use App\Services\RequestManager\ActivityElementValidator;
use App\Services\SettingsManager;
use App\Services\Twitter\TwitterAPI;
use App\User;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Psr\Log\LoggerInterface;


/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    /**
     * @var Identifier
     */
    protected $identifierForm;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var
     */
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
     * @var User
     */
    protected $user;

    /**
     * @var LoggerInterface
     */
    protected $loggerInterface;

    /**
     * @var ChangeActivityDefault
     */
    protected $changeActivityDefaultForm;

    /**
     * @var ChangeActivityDefaultManager
     */
    protected $changeActivityDefaultManager;
    /**
     * @var DocumentLinkManager
     */
    protected $documentLinkManager;

    protected $settings;

    /**
     * @var TwitterAPI
     */
    protected $twitter;

    /**
     * @param SettingsManager              $settingsManager
     * @param SessionManager               $sessionManager
     * @param OrganizationManager          $organizationManager
     * @param Identifier                   $identifierForm
     * @param ActivityManager              $activityManager
     * @param ResultManager                $resultManager
     * @param TransactionManager           $transactionManager
     * @param DocumentLinkManager          $documentLinkManager
     * @param ChangeActivityDefault        $changeActivityDefaultForm
     * @param ChangeActivityDefaultManager $changeActivityDefaultManager
     * @param User                         $user
     * @param Settings                     $settings
     * @param LoggerInterface              $loggerInterface
     * @param TwitterAPI                   $twitterAPI
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        Identifier $identifierForm,
        ActivityManager $activityManager,
        ResultManager $resultManager,
        TransactionManager $transactionManager,
        DocumentLinkManager $documentLinkManager,
        ChangeActivityDefault $changeActivityDefaultForm,
        ChangeActivityDefaultManager $changeActivityDefaultManager,
        User $user,
        Settings $settings,
        LoggerInterface $loggerInterface,
        TwitterAPI $twitterAPI
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
        $this->settings                     = $settings;
        $this->user                         = $user;
        $this->loggerInterface              = $loggerInterface;
        $this->twitter                      = $twitterAPI;
        $this->documentLinkManager          = $documentLinkManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activities = $this->activityManager->getActivities($this->organization_id);
        foreach ($activities as $key => $activity) {
            if ($activity->activity_workflow == 3) {
                $filename                              = $this->getPublishedActivityFilename($this->organization_id, $activity);
                $filenames[$activity->id]              = $filename;
                $activityPublishedStatus               = $this->getPublishedActivityStatus($filename, $this->organization_id);
                $activityPublishedStats[$activity->id] = $activityPublishedStatus;
                $message                               = $this->getMessageForPublishedActivity($activityPublishedStatus, $filename);
                $messages[$activity->id]               = $message;
            }
        }

        return view('Activity.index', compact('activities', 'filenames', 'activityPublishedStats', 'messages'));
    }

    /**
     * store the activity identifier
     * @param IatiIdentifierRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IatiIdentifierRequest $request)
    {
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        $this->authorize('add_activity', $organization);
        $settings           = $this->settingsManager->getSettings($this->organization_id);
        $defaultFieldValues = $settings->default_field_values;

        if (!$defaultFieldValues) {
            $response = ['type' => 'warning', 'code' => ['default_values', ['name' => 'activity']]];

            return redirect('/default-values')->withResponse($response);
        }

        $input  = $request->all();
        $result = $this->activityManager->store($input, $this->organization_id, $defaultFieldValues);

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
        $activityData = $this->activityManager->getActivityData($id);

        if ($activityData->activity_workflow == 3) {
            $filename                = $this->getPublishedActivityFilename($this->organization_id, $activityData);
            $activityPublishedStatus = $this->getPublishedActivityStatus($filename, $this->organization_id);
            $message                 = $this->getMessageForPublishedActivity($activityPublishedStatus, $filename);
        }

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $activityDataList                   = $activityData->activity_data_list;
        $activityResult                     = $this->resultManager->getResults($id)->toArray();
        $activityTransaction                = $this->transactionManager->getTransactions($id)->toArray();
        $activityDocumentLinks              = $this->documentLinkManager->getDocumentLinks($id)->toArray();
        $activityDataList['results']        = $activityResult;
        $activityDataList['transaction']    = $activityTransaction;
        $activityDataList['document_links'] = $activityDocumentLinks;
        $activityDataList['reporting_org']  = $activityData->organization->reporting_org;

        if ($activityDataList['activity_workflow'] == 0) {
            $nextRoute = route('activity.complete', $id);
        } elseif ($activityDataList['activity_workflow'] == 1) {
            $nextRoute = route('activity.verify', $id);
        } else {
            $nextRoute = route('activity.publish', $id);
        }

        return view('Activity.show', compact('activityDataList', 'id', 'filename', 'activityPublishedStatus', 'message', 'nextRoute'));
    }

    /**
     * @param                          $id
     * @param Request                  $request
     * @param ActivityElementValidator $activityElementValidator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request, ActivityElementValidator $activityElementValidator)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activityData);
        $input            = $request->all();
        $activityWorkflow = $input['activity_workflow'];

        if ($activityWorkflow == 3) {
            $this->authorize('publish_activity', $activityData);
        }

        $settings        = $this->settingsManager->getSettings($activityData['organization_id']);
        $transactionData = $this->activityManager->getTransactionData($id);
        $resultData      = $this->activityManager->getResultData($id);
        $organization    = $this->organizationManager->getOrganization($activityData->organization_id);
        $orgElem         = $this->organizationManager->getOrganizationElement();
        $activityElement = $this->activityManager->getActivityElement();
        $xmlService      = $activityElement->getActivityXmlService();

        if ($activityWorkflow == 1) {
            $validationMessage = $activityElementValidator->validateActivity($activityData, $transactionData);

            if ($validationMessage) {
                $response = ['type' => 'warning', 'code' => ['message', ['message' => $validationMessage]]];

                return redirect()->back()->withResponse($response);
            }

            $messages = $xmlService->validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);

            if ($messages != []) {
                $response = ['type' => 'danger', 'messages' => $messages, 'activity' => 'true'];

                return redirect()->back()->withResponse($response);
            }
        } elseif ($activityWorkflow == 3) {
            if (empty($settings['registry_info'][0]['publisher_id']) && empty($settings['registry_info'][0]['api_id'])) {
                $response = ['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]];

                return redirect()->to('/settings')->withResponse($response);
            }
            $xmlService->generateActivityXml(
                $activityData,
                $transactionData,
                $resultData,
                $settings,
                $activityElement,
                $orgElem,
                $organization
            );

            if ($settings['registry_info'][0]['publish_files'] == 'yes') {
                $publishedStatus = $this->publishToRegistry();
                $this->activityManager->updateStatus($input, $activityData);

                if ($publishedStatus) {
                    $this->activityManager->makePublished($activityData);
                    $this->activityManager->activityInRegistry($activityData);
                    $this->twitter->post($settings, $organization);
                    $response = ['type' => 'success', 'code' => ['publish_registry_publish', ['name' => '']]];

                    return redirect()->back()->withResponse($response);
                } else {
                    $response = ['type' => 'warning', 'code' => ['publish_registry', ['name' => '']]];

                    return redirect()->back()->withResponse($response);
                }
            }
        }

        $statusLabel = ['Completed', 'Verified', 'Published'];
        $response    = ($this->activityManager->updateStatus($input, $activityData)) ?
            ['type' => 'success', 'code' => ['activity_statuses', ['name' => $statusLabel[$activityWorkflow - 1]]]] :
            [
                'type' => 'danger',
                'code' => ['activity_statuses_failed', ['name' => $statusLabel[$activityWorkflow - 1]]],
            ];

        return redirect()->back()->withResponse($response);
    }

    /**
     * @return bool
     */
    public function publishToRegistry()
    {
        $activityPublishedFiles = $this->activityManager->getActivityPublishedFiles($this->organization_id);

        $settings = $this->settingsManager->getSettings($this->organization_id);
        $api_url  = config('filesystems.iati_registry_api_base_url');
        $apiCall  = new CkanClient($api_url, $settings['registry_info'][0]['api_id']);

        try {
            foreach ($activityPublishedFiles as $publishedFile) {
                $data = $this->generateJson($publishedFile);

                $this->loggerInterface->info(
                    'Payload for publishing',
                    ['payload' => $data, 'by_user' => auth()->user()->name]
                );

                if ($settings->publishing_type == "segmented") {
                    $filename = explode('-', $publishedFile->filename);
                    $code     = str_replace('.xml', '', end($filename));
                }

                if ($publishedFile['published_to_register'] == 0) {
                    $apiCall->package_create($data);
                    $this->activityManager->updatePublishToRegister($publishedFile->id);
                } elseif ($publishedFile['published_to_register'] == 1) {
//                    $package = ($settings->publishing_type == "segmented") ? $settings['registry_info'][0]['publisher_id'] . '-' . $code : $settings['registry_info'][0]['publisher_id'] . '-activities';
                    $apiCall->package_update($data);
                }

                $this->loggerInterface->info(
                    'Activity file published to registry.',
                    ['payload' => $data, 'by_user' => auth()->user()->name]
                );
            }

            return true;
        } catch (\Exception $e) {
            $this->loggerInterface->error($e);

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

        $title = ($settings->publishing_type == "segmented") ? $organization->name . ' Activity File-' . $code : $organization->name . ' Activity File';
        $name  = ($settings->publishing_type == "segmented") ? $settings['registry_info'][0]['publisher_id'] . '-' . $code : $settings['registry_info'][0]['publisher_id'] . '-activities';

        $requiredData = [
            'title'          => $title,
            'name'           => $name,
            'author_email'   => $author_email,
            'owner_org'      => $settings['registry_info'][0]['publisher_id'],
            'file_url'       => url(sprintf('files/xml/%s', $publishedFile->filename)),
            'geographicKey'  => $key,
            'geographicCode' => $code,
            'data_updated'   => $publishedFile->updated_at->toDateTimeString(),
            'activity_count' => count($publishedFile->published_activities),
            'language'       => config('app.locale')
        ];

        return $this->generatePayload($requiredData);
    }

    /**
     * Returns the request header payload while publishing any files to the IATI Registry.
     * @param $data
     * @return array
     */
    protected function generatePayload($data)
    {
        return json_encode(
            [
                'title'                => $data['title'],
                'name'                 => $data['name'],
                'author_email'         => $data['author_email'],
                'owner_org'            => $data['owner_org'],
                'license_id'           => 'other-open',
                'resources'            => [
                    [
                        'format'   => config('xmlFiles.format'),
                        'mimetype' => config('xmlFiles.mimeType'),
                        'url'      => $data['file_url'],
                    ],
                ],
                "filetype"             => "activity",
                $data['geographicKey'] => ($data['geographicCode'] == 'activities') ? '' : $data['geographicCode'],
                "data_updated"         => $data['data_updated'],
                "activity_count"       => $data['activity_count'],
                "language"             => $data['language'],
                "verified"             => "no"
            ]
        );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);

        $response = ($this->activityManager->destroy($activity)) ? [
            'type' => 'success',
            'code' => ['deleted', ['name' => 'Activity']],
        ] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'Activity']],
        ];

        return redirect()->back()->withResponse($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        $file = $this->getActivityPublishedFile($id);

        if (Gate::denies('ownership', $file)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $file);
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
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activityData);
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
    public function updateActivityDefault(
        $activityId,
        Request $request,
        ChangeActivityDefaultRequest $changeActivityDefaultRequest
    ) {
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activityData);
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
     * show identifier form to duplicate activity
     * @param $activityId
     * @return \Illuminate\View\View
     */
    public function duplicateActivity($activityId)
    {
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

// create permission
        return $this->create(true, $activityId);
    }

    /**
     * @param bool $duplicate
     * @param int  $activityId
     * @return \Illuminate\View\View
     */
    public function create($duplicate = false, $activityId = 0)
    {
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        if (Gate::denies('create', $organization)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organization);
        $form     = $duplicate ? $this->identifierForm->duplicate($activityId) : $this->identifierForm->create();
        $settings = $this->settingsManager->getSettings($this->organization_id);

        if ($organization->reporting_org == null || $organization->reporting_org[0]['reporting_organization_identifier'] == "" || $organization->reporting_org[0]['reporting_organization_type'] == "") {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        } elseif ($settings == null) {
            $response = ['type' => 'warning', 'code' => ['default_values', ['name' => 'activity']]];

            return redirect('/default-values')->withResponse($response);
        }elseif (!$settings->default_field_groups){
            $response = ['type' => 'warning', 'code' => ['default_field_groups_required', ['name' => 'activity']]];

            return redirect('/activity-elements-checklist')->withResponse($response);
        }

        $defaultFieldValues    = $settings->default_field_values;
        $reportingOrganization = $organization->reporting_org;

        return view(
            'Activity.create',
            compact('form', 'organization', 'reportingOrganization', 'defaultFieldValues', 'duplicate')
        );
    }

    /**
     * duplicate activity
     * @param                       $activityId
     * @param IatiIdentifierRequest $request
     * @return mixed
     */
    public function duplicateActivityAction($activityId, IatiIdentifierRequest $request)
    {
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activityData);
        $newItem                        = $activityData->replicate();
        $input                          = $request->all();
        $newItem->identifier            = [
            "activity_identifier"  => $input['activity_identifier'],
            "iati_identifier_text" => $input['iati_identifier_text'],
        ];
        $newItem->activity_workflow     = 0;
        $newItem->published_to_registry = 0;
        $result                         = $this->activityManager->duplicateActivityAction($newItem);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['duplication_failed', []]];

            return redirect()->back()->withInput()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['duplicated', ['url' => route('activity.show', [$newItem->id])]]];

        return redirect('/activity')->withResponse($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activityBulkPublishToRegistry(Request $request)
    {
        $files        = $request->get('activity_files');
        $settings     = $this->settingsManager->getSettings(session('org_id'));
        $organization = $this->organizationManager->getOrganization(session('org_id'));
        if (is_null($files)) {
            $response = [
                'type' => 'warning',
                'code' => ['message', ['message' => 'Please select activity XML files to be published.']],
            ];

            return redirect()->back()->withResponse($response);
        }
        $pubFiles   = [];
        $unpubFiles = [];
        $value      = [];

        foreach ($files as $data) {
            $orgId    = explode(':', $data)[0];
            $filename = explode(':', $data)[1];

            $publishedFile = $this->activityManager->getActivityPublishedData($filename, $orgId);
            $result        = $this->publishToRegistryForBulk($publishedFile, $orgId);
            if ($result) {
                $pubFiles[] = $filename;
                $this->savePublishedDataInActivityRegistry($publishedFile);
            } else {
                $unpubFiles[] = $filename;
            }
        }

        if ($unpubFiles) {
            $value['unpublished'] = sprintf("The files %s could not be published to registry. Please try again.", implode(',', $unpubFiles));
        }

        if ($pubFiles) {
            $this->twitter->post($settings, $organization);
            $value['published'] = sprintf("The files %s have been published to registry", implode(',', $pubFiles));
        }

        return redirect()->back()->with('value', $value);
    }

    /**
     * @param $publishedFile
     * @param $orgId
     * @return bool
     */
    protected function publishToRegistryForBulk($publishedFile, $orgId)
    {
        $settings = $this->settingsManager->getSettings($orgId);
        $api_url  = config('filesystems.iati_registry_api_base_url');
        $apiCall  = new CkanClient($api_url, $settings['registry_info'][0]['api_id']);

        try {
            $data = $this->generateJson($publishedFile);

            if ($publishedFile['published_to_register'] == 0) {
                $apiCall->package_create($data);
                $this->activityManager->updatePublishToRegister($publishedFile->id);
            } elseif ($publishedFile['published_to_register'] == 1) {
                $apiCall->package_update($data);
            }

            $this->loggerInterface->info(
                'Successfully published selected activity files',
                [
                    'payload' => $data,
                    'by_user' => auth()->user()->name
                ]
            );

            return true;
        } catch (\Exception $e) {
            $this->loggerInterface->error($e);

            return false;
        }
    }

    public function savePublishedDataInActivityRegistry($publishedFile)
    {
        $files = $publishedFile->published_activities;

        foreach ($files as $xmlFile) {
            $activityId = array_last(
                explode('-', explode('.', $xmlFile)[0]),
                function ($value) {
                    return true;
                }
            );

            $transaction      = [];
            $recipientCountry = [];
            $recipientRegion  = [];
            $title            = [];

            $filePath = sprintf('%s%s%s', public_path('files'), config('filesystems.xml'), $xmlFile);
            if (file_exists($filePath)) {
                $xml = simplexml_load_string(file_get_contents($filePath));
                $xml = json_decode(json_encode($xml), true);
                if (isset($xml['iati-activity']['transaction'])) {
                    $xmlTransaction = $xml['iati-activity']['transaction'];
                    $transaction    = $this->activityManager->getTransactionForBulk($xmlTransaction);
                }

                if (isset($xml['iati-activity']['recipient-country'])) {
                    $xmlRecipientCountry = $xml['iati-activity']['recipient-country'];
                    $recipientCountry    = $this->activityManager->getRecipientCountryForBulk($xmlRecipientCountry);
                }

                $activityStatus = '';

                if (is_array($xml['iati-activity']['activity-status'])) {
                    $activityStatus = $xml['iati-activity']['activity-status']['@attributes']['code'];
                }

                $identifier = $xml['iati-activity']['iati-identifier'];

                if (is_array($xml['iati-activity']['title'])) {
                    if (count($xml['iati-activity']['title']['narrative']) == 1) {
                        $title = $xml['iati-activity']['title']['narrative'];
                    } elseif (count($xml['iati-activity']['title']['narrative']) > 1) {
                        $title = $xml['iati-activity']['title']['narrative'][0];
                    }
                }

                $xmlSector = is_array($xml['iati-activity']['sector']) ? $xml['iati-activity']['sector'] : [];
                $sector    = $this->activityManager->getSectorForBulk($xmlSector);

                $jsonData = $this->activityManager->convertIntoJson($transaction, $activityStatus, $recipientRegion, $recipientCountry, $sector, $title, $identifier);
                $this->activityManager->saveBulkPublishDataInActivityRegistry($activityId, $jsonData);
            }
        }
    }

    /**
     * deletes activity element
     * @param $id
     * @param $element
     */
    public function deleteElement($id, $element)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $result = $this->activityManager->deleteElement($activity, $element);

        if ($result) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['activity_element_removed', ['element' => 'activity']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['activity_element_not_removed', ['element' => 'activity']]];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * Convert object of StdClass into an array for registry package update.
     * @param $data
     * @return array
     */
    protected function convertIntoArray($data)
    {
        return [
            'title'        => $data->title,
            'name'         => $data->name,
            'author_email' => $data->author_email,
            'owner_org'    => $data->owner_org,
            'resources'    => $data->resources,
            'extras'       => $data->extras,
        ];
    }

    /**
     * Get data from DB and generate xml
     * @param           $activityId
     * @param bool|null $viewErrors
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewActivityXml($activityId, $viewErrors = false)
    {
        $activityDataList = $this->activityManager->getActivityData($activityId);
        $activityElement  = $this->activityManager->getActivityElement();
        $xmlService       = $activityElement->getActivityXmlService();

        $xml = $xmlService->generateTemporaryActivityXml(
            $this->activityManager->getActivityData($activityId),
            $this->activityManager->getTransactionData($activityId),
            $this->activityManager->getResultData($activityId),
            $this->settingsManager->getSettings($activityDataList['organization_id']),
            $activityElement,
            $this->organizationManager->getOrganizationElement(),
            $this->organizationManager->getOrganization($activityDataList->organization_id)
        );

        $xmlLines = $xmlService->getFormattedXml($xml);
        $messages = $xmlService->getSchemaErrors($xml, session('version'));

        return view('Activity.xmlView', compact('xmlLines', 'messages', 'activityDataList', 'activityId', 'viewErrors'));
    }

    /**
     * Download of activity xml files
     * @param $activityId
     * @return \Illuminate\Http\Response
     */
    public function downloadActivityXml($activityId)
    {
        $activityData    = $this->activityManager->getActivityData($activityId);
        $activityElement = $this->activityManager->getActivityElement();
        $xmlService      = $activityElement->getActivityXmlService();

        $xml = $xmlService->generateTemporaryActivityXml(
            $this->activityManager->getActivityData($activityId),
            $this->activityManager->getTransactionData($activityId),
            $this->activityManager->getResultData($activityId),
            $this->settingsManager->getSettings($activityData['organization_id']),
            $activityElement,
            $this->organizationManager->getOrganizationElement(),
            $this->organizationManager->getOrganization($activityData->organization_id)
        );

        return response()->make(
            $xml,
            200,
            [
                'Content-type'        => 'text/xml',
                'Content-Disposition' => sprintf('attachment; filename=activityXmlFile.xml')
            ]
        );
    }

    /** Returns the filename that is generated when activity is published based on publishing type.
     * @param $organization_id
     * @param $activity
     * @return string
     */
    public function getPublishedActivityFilename($organization_id, $activity)
    {
        $settings       = $this->settings->where('organization_id', $organization_id)->first();
        $publisherId    = $settings->registry_info[0]['publisher_id'];
        $publishingType = $settings->publishing_type;

        if ($publishingType != "segmented") {
            $endName = 'activities';
        } else {
            $activityElement = $this->activityManager->getActivityElement();
            $xmlService      = $activityElement->getActivityXmlService();
            $endName         = $xmlService->segmentedXmlFile($activity);
        }
        $filename = sprintf('%s' . '-' . '%s.xml', $publisherId, $endName);

        return $filename;
    }

    /** Returns according to published to registry status of the activity.
     * @param $filename
     * @param $organization_id
     * @return string
     */
    public function getPublishedActivityStatus($filename, $organization_id)
    {
        $activityPublished   = $this->activityManager->getActivityPublishedData($filename, $organization_id);
        $settings            = $this->settings->where('organization_id', $organization_id)->first();
        $autoPublishSettings = $settings->registry_info[0]['publish_files'];
        $status              = 'Unlinked';

        if ($activityPublished) {
            if ($autoPublishSettings == "no") {
                ($activityPublished->published_to_register == 0) ? $status = "Unlinked" : $status = "Linked";
            } else {
                ($activityPublished->published_to_register == 0) ? $status = "unlinked" : $status = "Linked";
            }
        }

        return $status;
    }

    /** Returns message according to the status of the activity
     * @param $status
     * @param $filename
     * @return string
     */
    protected function getMessageForPublishedActivity($status, $filename)
    {
        if ($status == "Unlinked") {
            $message = "This activity has not been published to the IATI registry. Please go to Published files to manually publish your file to the registry. If you need help please
                                    contact us at <a href='mailto:support@aidstream.org'>support@aidstream.org</a>.";
        } elseif ($status == "Linked") {
            $message = " This activity has been published to the IATI registry. It is included in the file
                                    <a href='/files/xml/$filename'>$filename</a>";
        } else {
            $message = "This activity has not been published to the IATI registry. Please re-publish this activity again. If you need help please
                                    contact us at <a href='mailto:support@aidstream.org'>support@aidstream.org</a>.";
        }

        return $message;
    }

    /**
     * Remove sector details from the activity.
     * @param $activityId
     */
    public function removeActivitySector($activityId)
    {
        $activity = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $sector = $this->activityManager->removeActivitySector($activityId);

        if ($sector) {
            $response = ['type' => 'success', 'messages' => ['Sector details has been removed from Activity level.']];
        } else {
            $response = ['type' => 'danger', 'messages' => ['Failed to remove Sector details from Activity level.']];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * Remove all the sector details from every transactions of the activity.
     * @param $activityId
     */
    public function removeTransactionSector($activityId)
    {
        $activity = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $sector = $this->activityManager->removeTransactionSector($activityId);

        if ($sector) {
            $response = ['type' => 'success', 'messages' => ['Sector details has been removed from Transaction level.']];
        } else {
            $response = ['type' => 'danger', 'messages' => ['Failed to remove Sector details from Transaction level.']];
        }

        return redirect()->back()->withResponse($response);
    }
}
