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
use Illuminate\Support\Facades\Gate;
use App\Http\API\CKAN\CkanClient;
use App\User;
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
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        $this->authorize('add_activity', $organization);
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
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

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

            if ($settings['registry_info'][0]['publish_files'] == 'yes') {
                $publishedStatus = $this->publishToRegistry();
                $this->activityManager->updateStatus($input, $activityData);

                if ($publishedStatus) {
                    $this->activityManager->makePublished($activityData);
                    $this->activityManager->activityInRegistry($activityData);
                    $this->twitterPost();
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

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);

        $response = ($this->activityManager->destroy($activity)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Activity']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'Activity']]
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
    public function updateActivityDefault($activityId, Request $request, ChangeActivityDefaultRequest $changeActivityDefaultRequest)
    {
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
     * @return bool
     */
    public function publishToRegistry()
    {
        $activityPublishedFiles = $this->activityManager->getActivityPublishedFiles($this->organization_id);

        $settings               = $this->settingsManager->getSettings($this->organization_id);
        $api_url                = config('filesystems.iati_registry_api_base_url');
        $apiCall                = new CkanClient($api_url, $settings['registry_info'][0]['api_id']);

        try {
            foreach ($activityPublishedFiles as $publishedFile) {
                $data = $this->generateJson($publishedFile);

                $this->loggerInterface->info('Payload for publishing', ['payload' => $data, 'by_user' => auth()->user()->name]);

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

                $this->loggerInterface->info('Activity file published to registry.', ['payload' => $data, 'by_user' => auth()->user()->name]);
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
        $newItem->identifier            = ["activity_identifier" => $input['activity_identifier'], "iati_identifier_text" => $input['iati_identifier_text']];
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
            'extras'       => $data->extras
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activityBulkPublishToRegistry(Request $request)
    {
        $files = $request->get('activity_files');
        if (is_null($files)) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => 'Please select activity XML files to be published.']]];

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
                $this->twitterPost();
                $this->savePublishedDataInActivityRegistry($publishedFile);
            } else {
                $unpubFiles[] = $filename;
            }
        }

        if ($unpubFiles) {
            $value['unpublished'] = sprintf("The files %s could not be published to registry. Please try again.", implode(',', $unpubFiles));
        }

        if ($pubFiles) {
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
            $this->loggerInterface->info(
                'Successfully published selected activity files',
                [
                    'payload' => $data,
                    'by_user' => auth()->user()->name
                ]
            );

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

                $activityStatus = $xml['iati-activity']['activity-status']['@attributes']['code'];

                $identifier = $xml['iati-activity']['iati-identifier'];

                if (count($xml['iati-activity']['title']['narrative']) == 1) {
                    $title = $xml['iati-activity']['title']['narrative'];
                } elseif (count($xml['iati-activity']['title']['narrative']) > 1) {
                    $title = $xml['iati-activity']['title']['narrative'][0];
                }

                $xmlSector = $xml['iati-activity']['sector'];
                $sector    = $this->activityManager->getSectorForBulk($xmlSector);

                $jsonData          = $this->activityManager->convertIntoJson($transaction, $activityStatus, $recipientRegion, $recipientCountry, $sector, $title, $identifier);
                $explodeActivityId = explode('.', explode('-', $xmlFile)[1]);
                $activityId        = $explodeActivityId[0];
                $this->activityManager->saveBulkPublishDataInActivityRegistry($activityId, $jsonData);

            }
        }
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
                'title'        => $data['title'],
                'name'         => $data['name'],
                'author_email' => $data['author_email'],
                'owner_org'    => $data['owner_org'],
                'license_id'   => 'other-open',
                'resources'    => [
                    [
                        'format'   => config('xmlFiles.format'),
                        'mimetype' => config('xmlFiles.mimeType'),
                        'url'      => $data['file_url']
                    ]
                ],
                'extras'       => [
                    ['key' => 'filetype', 'value' => 'activity'],
                    ['key' => $data['geographicKey'], 'value' => $data['geographicCode']],
                    ['key' => 'data_updated', 'value' => $data['data_updated']],
                    ['key' => 'activity_count', 'value' => $data['activity_count']],
                    ['key' => 'language', 'value' => $data['language']],
                    ['key' => 'verified', 'value' => 'no']
                ]
            ]
        );
    }

    /**
     * deletes activity element
     * @param $id
     * @param $element
     * @return mixed
     */
    public function deleteElement($id, $element)
    {
        $activity = $this->activityManager->getActivityData($id);
        $result   = $this->activityManager->deleteElement($activity, $element);
        if ($result) {
            $response = ['type' => 'success', 'code' => ['activity_element_removed', ['element' => 'activity']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['activity_element_not_removed', ['element' => 'activity']]];
        }

        return redirect()->back()->withResponse($response);
    }

    /*
     * tweet if organization published their activities
     */
    public function twitterPost()
    {
        $settings = $this->settingsManager->getSettings(session('org_id'));
        $apiId    = $settings['registry_info'][0]['publisher_id'];

        $org = $this->organizationManager->getOrganization(session('org_id'));
        $this->activityManager->postInTwitter($apiId, $org);
    }
}
