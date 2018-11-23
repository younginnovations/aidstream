<?php namespace App\Http\Controllers\Np\Auth;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Controller;
use App\Models\SystemVersion;
use App\Services\NpRegistration;
use App\Services\RegistrationAgencies;
use App\Services\RequestManager\RegisterOrganization;
use App\Services\RequestManager\RegisterUsers;
use App\Services\RequestManager\Register;
use App\Services\Verification;

/**
 * Class RegistrationController
 * @package App\Http\Controllers\Auth
 */
class RegistrationController extends Controller
{
    /**
     * @var BaseForm
     */
    protected $baseForm;
    /**
     * @var Registration
     */
    protected $registrationManager;
    /**
     * @var Verification
     */
    protected $verificationManager;
    /**
     * @var RegistrationAgencies
     */
    protected $regAgencyManager;
    /**
     * @var SystemVersion
     */
    protected $systemVersion;

    /**
     * @param BaseForm             $baseForm
     * @param Registration         $registrationManager
     * @param Verification         $verificationManager
     * @param RegistrationAgencies $regAgencyManager
     * @param SystemVersion        $systemVersion
     */
    public function __construct(BaseForm $baseForm, NpRegistration $registrationManager, Verification $verificationManager, RegistrationAgencies $regAgencyManager, SystemVersion $systemVersion)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->baseForm            = $baseForm;
        $this->registrationManager = $registrationManager;
        $this->verificationManager = $verificationManager;
        $this->regAgencyManager    = $regAgencyManager;
        $this->systemVersion       = $systemVersion;
    }

    /**
     * returns registration view
     * @param null $systemVersion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm($systemVersion = null)
    {
        $regInfo        = session()->pull('reg_info');
        $orgType        = $this->baseForm->getCodeList('OrganizationType', 'Organization', false);
        $countries      = $this->baseForm->getCodeList('Country', 'Organization', false);
        $orgRegAgency   = $this->baseForm->getCodeList('NpOrganisationRegistrationAgency', 'Organization', false);
        $dbRegAgency    = $this->regAgencyManager->getRegAgenciesCode();
        $orgRegAgency   = array_merge($orgRegAgency, $dbRegAgency);
        $systemVersions = $this->systemVersion->where([ ['id', '<>', config('system-version.Tz.id')], ['id', '<>', config('system-version.Np.id')] ])->lists('system_version', 'id')->toArray();
        $dbRoles = \DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $districtsArray = \DB::table('districts')->get();
        $municipalitiesArray = \DB::table('municipalities')->get();

        $roles   = [];
        foreach ($dbRoles as $role) {
            $roles[$role->id] = $role->role;
        }

        $data = [];
        foreach ($orgRegAgency as $agencyShortName => $agency) {
            if ($agencyShortName !== "") {
                $data[$agencyShortName] = sprintf('%s - %s', $agencyShortName, $agency);
            }
        }

        $districts = [];
        foreach($districtsArray as $district){
            $districts[$district->id] = $district->name;
        }
        
        $municipalities = [];
        foreach($municipalitiesArray as $municipality){
            $municipalities[$municipality->id] = $municipality->name;
        }

        $orgRegAgency = $data;


        return view('np.auth.register', compact('orgType', 'districts','municipalities','municipalitiesArray', 'countries', 'orgRegAgency', 'roles', 'regInfo', 'systemVersions', 'systemVersion'));
    }

    /**
     * save organization info and users
     * @param Register $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Register $request)
    {
        $request = request();

        $systemVersion = isTzSubDomain() ? 3 : isNpSubDomain() ? 4 : $request->get('systemVersion');
        $users         = $request->get('users');
        $orgInfo       = $request->get('organization');

        if ($organization = $this->registrationManager->register($orgInfo, $users, $systemVersion)) {
            return $this->postRegistration($organization);
        } else {
            $response = ['type' => 'danger', 'code' => ['failed_registration']];

            return redirect()->back()->withInput()->withResponse($response);
        }
    }

    /**
     * sends emails to users after registration
     * @param $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function postRegistration($organization)
    {
        $user = $organization->users->where('role_id', 1)->first();
        $this->verificationManager->sendVerificationEmail($user);

        return redirect()->route('registration')->withEmail($user->email)->withTab('#tab-verification');
    }

    /**
     * show similar organizations
     * @param null $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSimilarOrganizations($type = null)
    {
        session()->put('reg_info', request()->except('_token'));
        $orgName = request('organization.organization_name');

        return view('auth.similarOrg', compact('orgName', 'type'));
    }

    /**
     * show same organization identifier verification
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSameOrgIdentifier()
    {
        $orgIdentifier = request('organization.organization_identifier');
        $orgInfo       = $this->registrationManager->checkOrgIdentifier($orgIdentifier);
        $orgName       = $orgInfo['org_name'];
        $adminName     = $orgInfo['admin_name'];
        session()->put('same_identifier_org_id', $orgInfo['org_id']);

        return view('auth.sameOrgIdentifier', compact('orgName', 'adminName', 'orgIdentifier'));
    }

    /**
     * returns list of similar organizations
     * @param $orgName
     * @return array
     */
    public function listSimilarOrganizations($orgName)
    {
        $similarOrganizations = $this->registrationManager->getSimilarOrg($orgName);

        return $this->registrationManager->prepareSimilarOrg($similarOrganizations);
    }

    /**
     * @param null $type
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function submitSimilarOrganization($type = null)
    {
        if ($type) {
            $orgId = session()->pull('same_identifier_org_id');
        } else {
            $orgId = request('similar_organization');
            $type  = request('type');
        }
        if ($orgId && $type == 'admin') {
            if ($secondaryContact = $this->registrationManager->hasSecondaryContact($orgId)) {
                if ($this->registrationManager->sendRecoveryEmail($orgId, $secondaryContact['email'])) {
                    return redirect()->to('/')->withSecondaryContactName(trim($secondaryContact['first_name'] . ' ' . $secondaryContact['last_name']));
                } else {
                    return redirect()->back()->withErrors(['email' => trans('error.failed_to_send_email')]);
                }
            } else {
                return redirect()->route('contact', ['no-secondary-contact-support']);
            }
        } elseif ($orgId && ($type == '' || $type == 'user')) {
            $this->checkOrgIdentifier($orgId);

            return redirect()->route('contact', ['contact-admin-for-same-org']);
        } elseif (!request('similar_organization') && ($type == 'admin' || $type == 'user')) {
            return redirect()->route('contact', ['not-my-organization']);
        }

        return redirect()->route('registration')->withTab('#tab-users');
    }

    /**
     * returns organization by identifier
     * @param null $orgId
     * @return array
     */
    public function checkOrgIdentifier($orgId = null)
    {
        if ($orgId = ($orgId ? $orgId : request('org_id'))) {
            $orgInfo = $this->registrationManager->getOrganization($orgId);
        } else {
            $orgInfo = $this->registrationManager->checkOrgIdentifier(request('org_identifier'));
            if ($orgInfo) {
                session()->put('same_identifier_org_id', $orgInfo['org_id']);
            }
        }
        if ($orgInfo && ($adminEmail = getVal($orgInfo, ['admin_email']))) {
            session()->put('admin_email', $adminEmail);
            session()->put('admin_name', getVal($orgInfo, ['admin_name']));
            session()->put('org_name', getVal($orgInfo, ['org_name']));
        }

        return $orgInfo;
    }
}
