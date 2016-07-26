<?php namespace App\Http\Controllers\Complete;

use App\Core\Form\BaseForm;
use App\Core\V201\Requests\Settings\DefaultValuesRequest;
use App\Core\V201\Requests\Settings\PublishingSettingsRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\OtherIdentifierManager;
use App;
use App\Services\RequestManager\Organization\SettingsRequestManager;
use App\Services\Settings\SettingsService;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Kris\LaravelFormBuilder\FormBuilder;
use Psr\Log\LoggerInterface;


/**
 * Class SettingsController
 * @package App\Http\Controllers\Complete
 */
class SettingsController extends Controller
{
    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var mixed
     */
    protected $settings;
    /**
     * @var App\Models\Organization\Organization
     */
    protected $organization;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var OtherIdentifierManager
     */
    protected $otherIdentifierManager;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var BaseForm
     */
    protected $baseForm;

    /**
     * @var LoggerInterface
     */
    protected $loggerInterface;

    /**
     * @param SettingsManager        $settingsManager
     * @param OrganizationManager    $organizationManager
     * @param ActivityManager        $activityManager
     * @param OtherIdentifierManager $otherIdentifierManager
     * @param SettingsService        $settingsService
     * @param LoggerInterface        $loggerInterface
     * @param BaseForm               $baseForm
     * @param FormBuilder            $formBuilder
     * @internal param FormBuilder $formBuilder
     * @internal param BaseForm $formBase
     */
    function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager,
        OtherIdentifierManager $otherIdentifierManager,
        BaseForm $baseForm,
        FormBuilder $formBuilder,
        SettingsService $settingsService,
        LoggerInterface $loggerInterface
    ) {
        $this->middleware('auth');
        $this->settingsManager        = $settingsManager;
        $org_id                       = Session::get('org_id');
        $this->settings               = $settingsManager->getSettings($org_id);
        $this->organization           = $organizationManager->getOrganization($org_id);
        $this->activityManager        = $activityManager;
        $this->otherIdentifierManager = $otherIdentifierManager;
        $this->settingsService        = $settingsService;
        $this->loggerInterface        = $loggerInterface;
        $this->baseForm               = $baseForm;
        $this->formBuilder            = $formBuilder;
    }

    /**
     * Display settings
     *
     * @param FormBuilder     $formBuilder
     * @param DatabaseManager $databaseManager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(FormBuilder $formBuilder, DatabaseManager $databaseManager)
    {
        $currentUser = auth()->user();

        if ($currentUser->isNotAdmin()) {
            return redirect()->back()->withResponse(
                [
                    'type' => 'warning',
                    'code' => [
                        'message',
                        ['message' => 'You do not have the correct privileges to view this page.']
                    ]
                ]
            );
        }
        $db_versions = $databaseManager->table('versions')->get();
        $versions    = [];

        foreach ($db_versions as $ver) {
            $versions[] = $ver->version;
        }

        $model = [];
        if (isset($this->settings->default_field_groups)) {
            $version = $this->settings->version;
//            $model['version_form']         = [['version' => $version]];
            $model['publishing_type']      = [['publishing' => $this->settings->publishing_type]];
            $model['registry_info']        = $this->settings->registry_info;
            $model['default_field_values'] = $this->settings->default_field_values;
            $model['default_field_groups'] = $this->settings->default_field_groups;
        } else {
            $version = config('app.default_version');
            $data    = '{"reporting_organization_info":[{"reporting_organization_identifier":"","reporting_organization_type":"10","organization_name":"","reporting_organization_language":"es"}],"publishing_type":[{"publishing":"unsegmented"}],"registry_info":[{"publisher_id":"","api_id":"","publish_files: ":"no"}],"default_field_values":[{"default_hierarchy":"1"}],"default_field_groups":[{"title":"Title","description":"Description","activity_status":"Activity Status","activity_date":"Activity Date","participating_org":"Participating Org","recipient_county":"Recipient Country","sector":"Sector","budget":"Budget","transaction":"Transaction"}]}';
            $model   = json_decode($data, true);
        }
        if (isset($this->organization)) {
            $model['reporting_organization_info'] = $this->organization->reporting_org;
        };
        $url         = (isset($this->settings) ? route('update-settings') : route('settings.store'));
        $method      = isset($this->settings) ? 'PUT' : 'POST';
        $formOptions = [
            'method' => $method,
            'url'    => $url
        ];
        if (!empty($model)) {
            $formOptions['model'] = $model;
        }
        $form = $formBuilder->create('App\Core\V201\Forms\SettingsForm', $formOptions);

        return view('settings', compact('form', 'version', 'versions'));
    }

    /**
     * Store settings
     * @param SettingsRequestManager $settingRequest
     * @return
     * @internal param TempRequest|SettingsRequestManager $request
     */
    public function store(SettingsRequestManager $settingRequest)
    {
        $currentUser = auth()->user();

        if ($currentUser->isNotAdmin()) {
            return redirect()->back()->withResponse(
                [
                    'type' => 'warning',
                    'code' => [
                        'message',
                        ['message' => 'You do not have the correct privileges to view this page.']
                    ]
                ]
            );
        }
        $input    = $settingRequest->requestHandler->all();
        $response = ($this->settingsManager->storeSettings($input, $this->organization)) ? ['type' => 'success', 'code' => ['created', ['name' => 'Settings']]] : [
            'type' => 'danger',
            'code' => [
                'save_failed',
                ['name' => 'Settings']
            ]
        ];

        return Redirect::to(config('app.admin_dashboard'))->withResponse($response);
    }

    /**
     * Update settings
     *
     * @param  int                   $id
     * @param SettingsRequestManager $settingRequest
     * @return
     * @internal param TempRequest|SettingsRequestManager $request
     */
    public function update($id, SettingsRequestManager $settingRequest)
    {
        $currentUser = auth()->user();

        if ($currentUser->isNotAdmin()) {
            return redirect()->back()->withResponse(
                [
                    'type' => 'warning',
                    'code' => [
                        'message',
                        ['message' => 'You do not have the correct privileges to view this page.']
                    ]
                ]
            );
        }
        $input             = $settingRequest->requestHandler->all();
        $newPublishingType = $input['publishing_type'][0]['publishing'];
        $oldIdentifier     = $this->organization->reporting_org[0]['reporting_organization_identifier'];
        $settings          = $this->settingsManager->getSettings($this->organization->id);
        $publishingType    = $settings->publishing_type;
        $activities        = $this->activityManager->getActivities($this->organization->id);
        if ($publishingType != $newPublishingType) {
            $publishedFiles = $this->activityManager->getActivityPublishedFiles(Session::get('org_id'));
            if (count($publishedFiles)) {
                $this->generateNewFiles($newPublishingType, $activities);
            }
        }
        $reportingOrgIdentifier = $input['reporting_organization_info'][0]['reporting_organization_identifier'];
        foreach ($activities as $activity) {
            $status          = $activity['published_to_registry'];
            $otherIdentifier = (array) $activity->other_identifier;
            if ($status == 1 && !in_array(["reference" => $oldIdentifier, "type" => "B1", 'owner_org' => []], $otherIdentifier) && ($oldIdentifier !== $reportingOrgIdentifier)) {
                $otherIdentifier[] = ['reference' => $oldIdentifier, 'type' => 'B1', 'owner_org' => []];
                $this->otherIdentifierManager->update(['other_identifier' => $otherIdentifier], $activity);
            }
        }
        $result = $this->settingsManager->updateSettings($input, $this->organization, $this->settings);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Settings']]];

            return redirect()->back()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Settings']]];

        return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
    }


    /**
     * generate new xml files with published activities
     * @param $newPublishingType
     * @param $activities
     */
    protected function generateNewFiles($newPublishingType, $activities)
    {
        $activityElement = $this->activityManager->getActivityElement();
        $xmlService      = $activityElement->getActivityXmlService();
        $orgIdentifier   = $this->organization->reporting_org[0]['reporting_organization_identifier'];
        $publisherId     = $this->settings->registry_info[0]['publisher_id'];
        if ($newPublishingType == "unsegmented") {
            $filename      = $publisherId . '-activities.xml';
            $activitiesXml = [];
            foreach ($activities as $activity) {
                if ($activity->activity_workflow == 3) {
                    $publishedActivity = sprintf('%s-%s.xml', $publisherId, $activity->id);
                    $this->generateXmlIfDoesNotExist($publishedActivity, $activity);
                    $activitiesXml[] = $publishedActivity;
                }
            }
            $xmlService->getMergeXml($activitiesXml, $filename);
            $xmlService->savePublishedFiles($filename, Session::get('org_id'), $activitiesXml);
        } elseif ($newPublishingType == "segmented") {
            $activitiesXml = [];
            foreach ($activities as $activity) {
                if ($activity->activity_workflow == 3) {
                    $filename          = sprintf('%s-%s.xml', $publisherId, $xmlService->segmentedXmlFile($activity));
                    $publishedActivity = sprintf('%s-%s.xml', $publisherId, $activity->id);
                    $this->generateXmlIfDoesNotExist($publishedActivity, $activity);
                    $activitiesXml[$filename][] = $publishedActivity;
                }
            }
            foreach ($activitiesXml as $filename => $xmlFiles) {
                $xmlService->getMergeXml($xmlFiles, $filename);
                $xmlService->savePublishedFiles($filename, Session::get('org_id'), $xmlFiles);
            }
        }
    }

    /**
     * generate xml file for particular activity if xml file does not exist
     * @param $publishedActivity
     * @param $activity
     */
    protected function generateXmlIfDoesNotExist($publishedActivity, $activity)
    {
        $filePath = public_path('files') . config('filesystems.xml') . $publishedActivity;

        if (!file_exists($filePath)) {
            $this->settingsManager->generateXml($activity);
        }
    }

    /**
     * Display form to view publishing information
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewPublishingInfo()
    {
        $settings = $this->settings;

        if ($settings) {
            $publishingInfo['publishing']          = $settings->publishing_type;
            $publishingInfo['publisher_id']        = (is_null($settings->registry_info)) ? '' : getVal($settings->registry_info, [0, 'publisher_id']);
            $publishingInfo['api_id']              = (is_null($settings->registry_info)) ? '' : getVal($settings->registry_info, [0, 'api_id']);
            $publishingInfo['publish_files']       = (is_null($settings->registry_info)) ? 'no' : getVal($settings->registry_info, [0, 'publish_files']);
            $publishingInfo['publisher_id_status'] = (is_null($settings->registry_info)) ? '' : getVal($settings->registry_info, [0, 'publisher_id_status']);
            $publishingInfo['api_id_status']       = (is_null($settings->registry_info)) ? '' : getVal($settings->registry_info, [0, 'api_id_status']);
        } else {
            $publishingInfo = [];
        }

        $url         = route('publishing-settings.update');
        $formOptions = [
            'method' => 'POST',
            'url'    => $url,
            'model'  => $publishingInfo
        ];
        $form        = $this->settingsManager->viewPublishingInfo($formOptions);

        return view('settings.publishingSettings', compact('form', 'settings'));
    }

    /**
     * save publishing information.
     * @param Request $request
     * @return mixed
     */
    public function savePublishingInfo(Request $request)
    {
        if ($this->settings) {
            $organizationId = session('org_id');
            $settings       = $request->all();

            if ($this->settingsService->hasSegmentationChanged($organizationId, $settings)) {
                $changes = $this->settingsService->getChangeLog($organizationId, $settings);

                if (empty($changes['previous']) && empty($changes['changes'])) {

                    return redirect()->route('publishing-settings')->withResponse(['type' => 'warning', 'messages' => ['You do not have any files for the segmentation change to take effect on . ']]);
                }

                return view('settings.change-log', compact('organizationId', 'changes', 'settings'));
            }
        }

        $publishing_info = $this->settingsManager->savePublishingInfo($request->all(), $this->settings);
        $response        = $this->getResponse($publishing_info, 'Publishing Settings');

        return redirect()->back()->withResponse($response);
    }

    /**
     * Display form to view default field values.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewDefaultValues()
    {
        $settings      = $this->settings;
        $defaultValues = ($settings) ? $settings->default_field_values[0] : '';
        $url           = route('default-values.update');
        $formOptions   = [
            'method' => 'POST',
            'url'    => $url,
            'model'  => $defaultValues
        ];

        $form = $this->settingsManager->viewDefaultValues($formOptions);

        return view('settings.defaultValues', compact('form', 'settings'));
    }

    /**
     * save default field values
     * @param DefaultValuesRequest $request
     * @return mixed
     */
    public function saveDefaultValues(DefaultValuesRequest $request)
    {
        $settings      = $this->settings;
        $defaultValues = $this->settingsManager->saveDefaultValues($request->except('_token'), $settings);

        if (!isset($settings->default_field_groups)) {
            $response = ['type' => 'warning', 'code' => ['default_field_groups_required', ['name' => 'activity']]];

            return redirect('activity-elements-checklist')->withResponse($response);
        }
        $response = $this->getResponse($defaultValues, 'Default Values ');

        return redirect()->back()->withResponse($response);
    }

    /**
     * Displays form of activity elements checklist
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewActivityElementsChecklist()
    {
        $checkedElements = ($this->settings) ? $this->settings->default_field_groups : '';
        $url             = route('activity-elements-checklist.update');
        $settings        = $this->settings;

        $formOptions = [
            'method' => 'POST',
            'url'    => $url,
            'model'  => ['default_field_groups' => $checkedElements]
        ];
        $form        = $this->settingsManager->viewActivityElementsChecklist($formOptions);

        return view('settings.activityElementsChecklist', compact('form', 'settings'));
    }

    /**
     * Save activity elements checklist
     * @param Request $request
     * @return mixed
     */
    public function saveActivityElementsChecklist(Request $request)
    {
        $default_field_groups      = $request->get('default_field_groups');
        $activityElementsChecklist = $this->settingsManager->saveActivityElementsChecklist($default_field_groups, $this->settings);
        $response                  = $this->getResponse($activityElementsChecklist, 'Activity Elements Checklist');

        return redirect()->back()->withResponse($response);
    }

    /**
     * Returns response after the data is submitted.
     * @param $method
     * @param $field
     * @return array
     */
    public function getResponse($method, $field)
    {
        $response = ($method) ? [
            'type' => 'success',
            'code' => ['updated', ['name' => $field]]
        ] : [
            'type' => 'danger',
            'code' => [
                'save_failed',
                ['name' => 'Settings']
            ]
        ];

        return $response;
    }

    /**
     *
     * @param PublishingSettingsRequest|Request $request
     * @return string
     */
    public function verifyPublisherAndApi(Request $request)
    {
        $apiKey      = $request->get('apiKey');
        $publisherId = $request->get('publisherId');

        $apiKeyResponse = $this->settingsManager->verifyApiKey($apiKey);
        $publisherId    = $this->settingsManager->verifyPublisherId($publisherId);

        $response = ['api_key' => $apiKeyResponse, 'publisher_id' => $publisherId];

        return $response;
    }

    /**
     * Update Settings with segmentation changes.
     * @param SettingsRequestManager $requestManager
     * @return mixed
     * @internal param TempRequest|Request $request
     */
    public function updateSettings(SettingsRequestManager $requestManager)
    {
        $organizationId = session('org_id');

        $settings     = $requestManager->requestHandler->all();
        $organization = $this->organization->findOrFail($organizationId);

        if (!$organization->publishedFiles->isEmpty()) {
            if ($this->settingsService->hasSegmentationChanged($organizationId, $settings)) {
                $changes = $this->settingsService->getChangeLog($organizationId, $settings);

                if (empty($changes['previous']) && empty($changes['changes'])) {

                    return redirect()->route('settings.index')->withResponse(['type' => 'warning', 'messages' => ['You do not have any files for the segmentation change to take effect on.']]);
                }

                return view('settings.change-log', compact('organizationId', 'changes', 'settings'));
            }
        }

        if (!$this->settingsManager->updateSettings($settings, $this->organization, $this->settings)) {
            $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Settings']]];
        } else {
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Settings']]];
        }

        return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
    }

    /**
     * Change the segmentation for an Organization.
     * @param Request $request
     * @return mixed
     */
    public function changeSegmentation(Request $request)
    {
        $segmentationChange = $this->settingsService->changeSegmentation($request->all());

        if (null === $segmentationChange || false === $segmentationChange) {
            $response = ['type' => 'warning', 'messages' => $this->getMessageFor($segmentationChange)];

            return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
        }
        if (!$this->settingsManager->savePublishingInfo(json_decode($request->get('settings'), true), $this->settings)) {
            $response = ['type' => 'danger', 'messages' => ['Failed to update Settings']];

            return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
        }
        $response = ['type' => 'success', 'messages' => ['Settings updated successfully.']];

        return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
    }

    /**
     * Returns message for the segmentationChange.
     * @param $segmentationChange
     * @return string
     */
    protected function getMessageFor($segmentationChange)
    {
        if (null === $segmentationChange) {
            return ['Could not change segmentation.'];
        }

        if (false === $segmentationChange) {
            return ['Could not publish to registry.'];
        }
    }
}
