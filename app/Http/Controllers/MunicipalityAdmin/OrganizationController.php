<?php namespace App\Http\Controllers\MunicipalityAdmin;

use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\SuperAdmin\Requests\Organization;
use App\SuperAdmin\Services\OrganizationGroupManager;
use App\SuperAdmin\Services\SuperAdminManager;
use App\Services\Activity\ActivityManager;
use Auth;
use App\Models\Settings;
use App\Np\Services\Activity\ActivityService;
use App\Np\Services\Activity\Transaction\TransactionService;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Database\DatabaseManager;

/**
 * Class OrganizationController
 * @package App\Http\Controllers\SuperAdmin
 */
class OrganizationController extends Controller
{
    const CORE_VERSION_ID = 1;

    const LITE_VERSION_ID = 2;
    /**
     * @var SuperAdminManager
     */
    protected $adminManager;

    protected $activityService;

    protected $transactionService;

    protected $organizationInfo;

    protected $settings;

    protected $activityManager;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var OrganizationGroupManager
     */
    protected $groupManager;

    protected $filteredOrganization = null;

    /**
     * @param SuperAdminManager        $adminManager
     * @param SettingsManager          $settingsManager
     * @param OrganizationGroupManager $groupManager
     */
    function __construct(SuperAdminManager $adminManager,ActivityManager $activityManager, SettingsManager $settingsManager, OrganizationGroupManager $groupManager, ActivityService $activityService, TransactionService $transactionService, Settings $settings)
    {
        $this->middleware('auth.municipalityAdmin');
        $this->adminManager    = $adminManager;
        $this->settingsManager = $settingsManager;
        $this->groupManager    = $groupManager;
        $this->activityService = $activityService;
        $this->transactionService = $transactionService;
        $this->settings        = $settings;
        $this->activityManager = $activityManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminDashboard()
    {
        return view('adminDashboard');
    }

    /**
     * get all organizations
     * @return \Illuminate\View\View
     */
    public function listOrganizations()
    {
        $organizations = (session('role_id') == 3) ? $this->adminManager->getOrganizations() : $this->groupManager->getGroupsByUserId(Auth::user()->id);

        return view('superAdmin.listOrganization', compact('organizations'));
    }

    /**
     * get all organizations
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function oldListOrganizations(Request $request)
    {
        if (isTzSubDomain()) {
            $organizations = $this->adminManager->getOrganizationBySystemVersion(config('system-version.Tz.id'));
        } else if (isNpSubDomain()) {
            $organizations = $this->adminManager->getOrganizationBySystemVersion(config('system-version.Np.id'));
        } else {
            if ($request->has('organization')) {
                $organizationName = $request->get('organization');
                $organizations    = $this->adminManager->getOrganizations($organizationName);
            } else {
                $organizations = (session('role_id') == 3) ? $this->adminManager->getOrganizations() : $this->groupManager->getGroupsByUserId(Auth::user()->id);
            }
        }

        return view('np.municipalityAdmin.oldListOrganization', compact('organizations', 'organizationName'));
    }

    public function listAllActivities()
    {
        $activities              = $this->activityService->listAll();

        // $stats                   = $this->activityService->getActivityStats();
        // $noOfPublishedActivities = $this->activityService->getNumberOfPublishedActivities($orgId);
        // $lastPublishedToIATI     = $this->activityService->lastPublishedToIATI($orgId);
        return view('np.municipalityAdmin.listActivities', compact('activities'));
    }

   /**
     * Display the detail of an activity.
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showActivity($activityId)
    {
        $activity = $this->activityService->find($activityId);
        $count    = [];
        $locationArray = \DB::table('activity_location')
                    ->leftjoin('municipalities', 'activity_location.municipality_id', '=', 'municipalities.id')
                    ->select('name', 'ward', 'municipality_id')
                    ->where('activity_id', '=', $activityId)
                    ->get();

        // if (Gate::denies('ownership', $activity)) {
        //     // return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        // }

        $version            = 'V202';
        $documentLinks      = $this->activityService->documentLinks($activityId, $version);
        $transaction        = $activity->transactions->toArray();
        $transactions       = $this->transactionService->getFilteredTransactions($transaction);
        $location           = $this->activityService->location($activity->toArray());
        $disbursement       = getVal($transactions, ['disbursement'], '');
        $expenditure        = getVal($transactions, ['expenditure'], '');
        $incoming           = getVal($transactions, ['incoming'], '');
        $defaultCurrency    = $this->transactionService->getDefaultCurrency($activity);
        $statusLabel        = ['draft', 'completed', 'verified', 'published'];
        $activityWorkflow   = $activity->activity_workflow;
        $btn_status_label   = ['Completed', 'Verified', 'Published'];
        $btn_text           = $activityWorkflow > 2 ? "" : $btn_status_label[$activityWorkflow];
        $recipientCountries = $this->activityService->getRecipientCountry($activity->recipient_country);

        if ($activity->activity_workflow == 3) {
            $filename                = $this->getPublishedActivityFilename($activity->organization_id, $activity);
            $activityPublishedStatus = $this->getPublishedActivityStatus($filename, $activity->organization_id);
            $message                 = $this->getMessageForPublishedActivity($activityPublishedStatus, $filename, $activity->organization);
        }

        if ($activity['activity_workflow'] == 0) {
            $nextRoute = route('np.activity.complete', $activityId);
        } elseif ($activity['activity_workflow'] == 1) {
            $nextRoute = route('np.activity.verify', $activityId);
        } else {
            $nextRoute = route('np.activity.publish', $activityId);
        }

        return view(
            'np.municipalityAdmin.showActivity',
            compact(
                'activity',
                'statusLabel',
                'activityWorkflow',
                'btn_text',
                'nextRoute',
                'disbursement',
                'expenditure',
                'incoming',
                'defaultCurrency',
                'activityPublishedStatus',
                'documentLinks',
                'location',
                'recipientCountries',
                'locationArray'
            )
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
    protected function getMessageForPublishedActivity($status, $filename, $organization)
    {
        $publisherId = getVal($organization->settings->toArray(), ['registry_info', 0, 'publisher_id'], null);
        $link        = $publisherId ? "<a href='https://iatiregistry.org/publisher/" . $publisherId . "' target='_blank'>IATI registry</a>" : "IATI Registry";

        if ($status == "Unlinked") {
            $message = trans('error.activity_not_published_to_registry');
        } elseif ($status == "Linked") {
            $message = trans('success.activity_published_to_registry', ['link' => $link]) . ' ' . "<a href='/files/xml/$filename'>$filename</a>";
        } else {
            $message = trans('error.republish_activity');
        }

        return $message;
    }

    /**
     * add new organization by superAdmin
     * @param FormBuilder $formBuilder
     * @param null        $orgId
     * @return \Illuminate\View\View
     */
    public function add(FormBuilder $formBuilder, $orgId = null)
    {
        $data  = '{"default_field_groups":[{"title":"Title","description":"Description","activity_status":"Activity Status","activity_date":"Activity Date","participating_org":"Participating Org","recipient_county":"Recipient Country","location":"Location","sector":"Sector","budget":"Budget","transaction":"Transaction","document_ink":"Document Link"}]}';
        $model = json_decode($data, true);
        if ($orgId) {
            $organizationInfo = $this->adminManager->getOrganizationUserBYId($orgId);
            $settings         = $this->settingsManager->getSettings($orgId);
            if ($settings) {
                $model['default_field_groups'] = $settings->default_field_groups;
                $model['default_field_values'] = $settings->default_field_values;
            }
            $model['organization_information'][0] = $organizationInfo[0];
            $model['admin_information'][0]        = $organizationInfo[0];
        }

        $form = $formBuilder->create(
            'App\SuperAdmin\Forms\Organization',
            [
                'method' => isset($organizationInfo) ? 'PUT' : 'POST',
                'url'    => isset($organizationInfo) ? route('admin.edit-organization', [$orgId]) : route('admin.add-organization'),
                'model'  => $model
            ]
        );

        return view('superAdmin.addOrganization', compact('form', 'orgId'));
    }

    /**
     * save the organization information in database
     * @param null         $orgId
     * @param Organization $organizationRequest
     * @return
     */
    public function save(Organization $organizationRequest, $orgId = null)
    {
        $orgData = $organizationRequest->all();
        (null !== $orgId) ? $this->adminManager->saveOrganization($orgData, $orgId) : $this->adminManager->saveOrganization($orgData);

        return redirect()->to('admin/list-organization')->withMessage('Organization ' . (null !== $orgId) ? 'updated' : 'added');
    }

    /**
     * update the organization status by superAdmin
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeOrganizationStatus($id, $status)
    {
        $organization         = $this->adminManager->getOrganizationById($id);
        $organization->status = $status;
        $organization->save();

        return redirect()->back();
    }

    /**
     * delete an organization by superAdmin
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOrganization($id)
    {
        $organization = $this->adminManager->getOrganizationById($id);
        $organization->delete($organization);
        app(Log::class)->activity(
            "activity.organization_deleted",
            [
                'org_name'    => $organization->name,
                'super_admin' => auth()->user()->username,
            ]
        );

        return redirect()->back()->withMessage('Organization has been deleted.');
    }

    /**
     * masquerade as an specific organization by superadmin
     * @param                 $orgId
     * @param                 $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function masqueradeOrganization($orgId, $userId)
    {
        $database = app(DatabaseManager::class);
        $settings = $this->settingsManager->getSettings($orgId);
        Session::put('org_id', $orgId);
        Session::put('system_version', $settings->organization->system_version_id);
        $current_version = (isset($settings)) ? $settings->version : config('app.default_version');
        Session::put('current_version', $current_version);
        $versions_db = $database->table('versions')->get();
        $versions    = [];

        foreach ($versions_db as $ver) {
            $versions[] = $ver->version;
        }
        $versionKey   = array_search($current_version, $versions);
        $next_version = (end($versions) == $current_version) ? null : $versions[$versionKey + 1];

        Session::put('next_version', $next_version);
        Session::put('version', 'V' . str_replace('.', '', $current_version));
        Auth::loginUsingId($userId);
        $organization = $settings->organization;

        if ($organization->system_version_id == self::CORE_VERSION_ID) {
            return redirect()->to(config('app.admin_dashboard'));
        }

        return redirect()->route(config('system-version.' . $organization->system_version_id)['route']);
    }

    /**
     * switch back to superAdmin role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchBackAsSuperAdmin()
    {
        $adminId = Session::get('admin_id');
        Auth::loginUsingId($adminId);

        return redirect()->to(config('app.super_admin_dashboard'));
    }

    /**
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hideOrganization($id, $status)
    {
        $organization          = $this->adminManager->getOrganizationById($id);
        $organization->display = $status;
        $organization->save();

        return redirect()->back();
    }

    /**
     *  Generates csv file containing details of the organization
     */
    public function exportOrganizationInfo()
    {
        $this->adminManager->exportDetails();
    }

    /**
     * Changes system version
     *
     * @param         $orgId
     * @param Request $request
     */
    public function changeSystemVersion($orgId, Request $request)
    {
        $system_version = $request->except('_token');

        if ($this->groupManager->updateSystemVersion($orgId, $system_version)) {
            return redirect()->back()->withResponse(['type' => 'success', 'code' => ['updated', ['name' => trans('global.system_version')]]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['update_failed', ['name' => trans('global.system_version')]]]);
    }
}
