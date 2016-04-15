<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
class SettingsController extends Controller
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

    /**
     * @param SettingsManager        $settingsManager
     * @param OrganizationManager    $organizationManager
     * @param ActivityManager        $activityManager
     * @param OtherIdentifierManager $otherIdentifierManager
     * @param LoggerInterface        $loggerInterface
     */
    function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager,
        OtherIdentifierManager $otherIdentifierManager,
        LoggerInterface $loggerInterface
    ) {
        $this->middleware('auth');
        $this->settingsManager        = $settingsManager;
        $org_id                       = Session::get('org_id');
        $this->settings               = $settingsManager->getSettings($org_id);
        $this->organization           = $organizationManager->getOrganization($org_id);
        $this->activityManager        = $activityManager;
        $this->otherIdentifierManager = $otherIdentifierManager;
        $this->loggerInterface        = $loggerInterface;
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
        $url         = (isset($this->settings) ? route('settings.update', [0]) : route('settings.store'));
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
        $filePath = config('filesystems.xml') . $publishedActivity;
        if (!file_exists($filePath)) {
            $this->settingsManager->generateXml($activity);
        }
    }
}
