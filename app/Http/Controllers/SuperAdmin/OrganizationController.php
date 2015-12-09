<?php namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\SuperAdmin\Requests\Organization;
use App\SuperAdmin\Services\OrganizationGroupManager;
use App\SuperAdmin\Services\SuperAdminManager;
use Auth;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class OrganizationController
 * @package App\Http\Controllers\SuperAdmin
 */
class OrganizationController extends Controller
{
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

    /**
     * @param SuperAdminManager        $adminManager
     * @param SettingsManager          $settingsManager
     * @param OrganizationGroupManager $groupManager
     */
    function __construct(SuperAdminManager $adminManager, SettingsManager $settingsManager, OrganizationGroupManager $groupManager)
    {
        $this->middleware('auth');
        $this->adminManager    = $adminManager;
        $this->settingsManager = $settingsManager;
        $this->groupManager    = $groupManager;
    }

    /**
     * get all organizations
     * @return \Illuminate\View\View
     */
    public function listOrganizations()
    {
        $organizations = (Auth::user()->role_id == 3) ? $this->adminManager->getOrganizations() : $this->groupManager->getGroupsByUserId(Auth::user()->id);

        return view('superAdmin.listOrganization', compact('organizations'));
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
            $model['organization_information'][0] = $organizationInfo;
            $model['admin_information'][0]        = $organizationInfo['users'][0];
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

        return redirect()->back()->withMessage('Organization has been deleted.');
    }

    /**
     * masquerade as an specific organization by superadmin
     * @param $orgId
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function masqueradeOrganization($orgId, $userId)
    {
        $adminId = Auth::user()->id;
        Session::put('org_id', $orgId);
        Session::put('admin_id', $adminId);
        Auth::loginUsingId($userId);

        return redirect()->to('/');
    }

    /**
     * switch back to superAdmin role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchBackAsSuperAdmin()
    {
        Session::forget('org_id');
        $adminId = Session::get('admin_id');
        Auth::loginUsingId($adminId);

        return redirect()->to('admin/dashboard');
    }
}
