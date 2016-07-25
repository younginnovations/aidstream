<?php namespace App\Http\Controllers\Complete;

use App\Core\Form\BaseForm;
use App\Core\V201\Requests\Settings\ActivityElementsChecklistRequests;
use App\Core\V201\Requests\Settings\DefaultValuesRequest;
use App\Core\V201\Requests\Settings\OrganizationInfoRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\OtherIdentifierManager;
use App\Services\RequestManager\Organization\SettingsRequestManager;
use App;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;

use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Psr\Log\LoggerInterface;


/**
 * Class SettingsController
 * @package App\Http\Controllers\Complete
 */
class SettingsController_old extends Controller
{
    protected $settingsManager;
    protected $settings;
    protected $organization;
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var OtherIdentifierManager
     */
    protected $otherIdentifierManager;

    protected $formBuilder;

    protected $baseForm;

    /**
     * @param SettingsManager        $settingsManager
     * @param OrganizationManager    $organizationManager
     * @param ActivityManager        $activityManager
     * @param OtherIdentifierManager $otherIdentifierManager
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
        LoggerInterface $loggerInterface,
        BaseForm $baseForm,
        FormBuilder $formBuilder
    ) {
        $this->middleware('auth');
        $this->settingsManager        = $settingsManager;
        $org_id                       = Session::get('org_id');
        $this->settings               = $settingsManager->getSettings($org_id);
        $this->organization           = $organizationManager->getOrganization($org_id);
        $this->activityManager        = $activityManager;
        $this->otherIdentifierManager = $otherIdentifierManager;
        $this->loggerInterface        = $loggerInterface;
        $this->baseForm               = $baseForm;
        $this->formBuilder            = $formBuilder;
    }

    /**
     * Display settings
     *
     * @param DatabaseManager $databaseManager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param FormBuilder $formBuilder
     */
    public function index(DatabaseManager $databaseManager)
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
        $url         = (isset($this->settings) ? route('settings.update', [0]) : route('settings.store'));
        $method      = isset($this->settings) ? 'PUT' : 'POST';
        $formOptions = [
            'method' => $method,
            'url'    => $url
        ];
        if (!empty($model)) {
            $formOptions['model'] = $model;
        }
        $form = $this->formBuilder->create('App\Core\V201\Forms\SettingsForm', $formOptions);

        return view('settings.settings', compact('form', 'version', 'versions'));
    }

    /**
     * Store settings
     * @param SettingsRequestManager $request
     */
    public function store(SettingsRequestManager $request)
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
        $input    = Input::all();
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
     * @param SettingsRequestManager $request
     */
    public function update($id, SettingsRequestManager $request)
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
        $input             = Input::all();
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

    public function viewPublishingInfo()
    {
        $settings                        = $this->settings;
        $publishingInfo['publishing']    = $settings->publishing_type;
        $publishingInfo['publisher_id']  = $settings->registry_info[0]['publisher_id'];
        $publishingInfo['api_id']        = $settings->registry_info[0]['api_id'];
        $publishingInfo['publish_files'] = $settings->registry_info[0]['publish_files'];

        $url         = route('publishing-settings.update');
        $formOptions = [
            'method' => 'PUT',
            'url'    => $url,
            'model'  => $publishingInfo
        ];
        $form        = $this->settingsManager->viewPublishingInfo($formOptions);

        return view('settings.publishing_settings', compact('form'));
    }

    public function savePublishingInfo(Request $request)
    {
        $publishing_info = $this->settingsManager->savePublishingInfo($request->all(), $this->settings);
        $response        = $this->getResponse($publishing_info, 'Publishing Settings');

        return redirect()->back()->withResponse($response);
    }

    public function viewDefaultValues()
    {
        $settings    = $this->settings;
        $url         = route('default_values.update');
        $formOptions = [
            'method' => 'PUT',
            'url'    => $url,
            'model'  => $settings->default_field_values[0]
        ];

        $form = $this->settingsManager->viewDefaultValues($formOptions);

        return view('settings.default_values', compact('form'));
    }

    public function saveDefaultValues(DefaultValuesRequest $request)
    {
        $defaultValues = $this->settingsManager->saveDefaultValues($request, $this->settings);
        $response      = $this->getResponse($defaultValues, 'Default Values ');

        return redirect()->back()->withResponse($response);
    }

    public function viewActivityElementsChecklist()
    {
        $checkedElements = $this->settings->default_field_groups;
        $url             = route('activity_elements_checklist.update');
        $formOptions     = [
            'method' => 'PUT',
            'url'    => $url,
            'model'  => ['default_field_groups' => $checkedElements]
        ];
        $form            = $this->settingsManager->viewActivityElementsChecklist($formOptions);

        return view('settings.activity_elements_checklist', compact('form'));
    }

    public function saveActivityElementsChecklist(Request $request)
    {
        $default_field_groups      = $request->get('default_field_groups');
        $activityElementsChecklist = $this->settingsManager->saveActivityElementsChecklist($default_field_groups, $this->settings);
        $response                  = $this->getResponse($activityElementsChecklist, 'Activity Elements Checklist');

        return redirect()->back()->withResponse($response);
    }

    public function viewOrganizationInformation()
    {
        $organization      = $this->organization;
        $organizationTypes = $this->baseForm->getCodeList('OrganizationType', 'Organization');
        $countries         = $this->baseForm->getCodeList('Country', 'Organization');
        $url               = route('organization_information.update');
        $formOptions       = [
            'method' => 'PUT',
            'url'    => $url,
            'model'  => ['narrative' => $organization->reporting_org[0]['narrative']]
        ];
        $form              = $this->settingsManager->viewOrganizationInformation($formOptions);

        return view('settings.organization_information', compact('form', 'organizationTypes', 'countries', 'organization'));
    }

    public function saveOrganizationInformation(OrganizationInfoRequest $request)
    {
        $organizationInfo = $this->settingsManager->saveOrganizationInformation($request->all(), $this->organization);
        $response         = $this->getResponse($organizationInfo, 'Organization Information');

        return redirect()->back()->withResponse($response);

    }

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
}
