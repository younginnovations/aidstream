<?php namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\SuperAdmin\Requests\Organization;
use App\SuperAdmin\Services\OrganizationGroupManager;
use App\SuperAdmin\Services\SuperAdminManager;
use Auth;
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

    protected $organizationInfo;
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
    function __construct(SuperAdminManager $adminManager, SettingsManager $settingsManager, OrganizationGroupManager $groupManager)
    {
        $this->middleware('auth.superAdmin');
        $this->adminManager    = $adminManager;
        $this->settingsManager = $settingsManager;
        $this->groupManager    = $groupManager;
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
        if(session('user_permission') == 8){
            return redirect()->intended(config('app.municipality_dashboard'));
        }
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

        return view('superAdmin.oldListOrganization', compact('organizations', 'organizationName'));
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
