<?php namespace App\Http\Controllers\Complete;

use App\Core\V201\Requests\UserPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\Activity\Activity;
use App\Models\UserActivity;
use App\Models\UserOnBoarding;
use App\Services\ActivityLog\ActivityManager;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Logging\Log as DbLogger;

/**
 * Class AdminController
 * @package App\Http\Controllers\Complete
 */
class AdminController extends Controller
{
    /**
     * @var
     */
    protected $org_id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @var DbLogger
     */
    protected $dbLogger;
    /**
     * @var ActivityManager
     */
    protected $userActivityManager;

    protected $settingsManager;

    /**
     * @param User                $user
     * @param OrganizationManager $organizationManager
     * @param DbLogger            $dbLogger
     * @param ActivityManager     $userActivityManager
     * @param SettingsManager     $settingsManager
     */
    function __construct(User $user, OrganizationManager $organizationManager, DbLogger $dbLogger, ActivityManager $userActivityManager, SettingsManager $settingsManager)
    {
        $this->middleware('auth');
        $this->org_id              = session('org_id');
        $this->user                = $user;
        $this->organizationManager = $organizationManager;
        $this->dbLogger            = $dbLogger;
        $this->userActivityManager = $userActivityManager;
        $this->settingsManager     = $settingsManager;
    }

    /**
     * @param null|string $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId = "all")
    {
        $activity      = $this->userActivityManager->getUserActivities($orgId);
        $organizations = $this->organizationManager->getOrganizations(['name', 'id']);

        return view('admin.activityLog', compact('activity', 'organizations', 'orgId'));
    }

    /**
     * display activity log data
     * @param              $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewData($id)
    {
        $data = $this->userActivityManager->getUserActivityData($id);

        return view('admin.logData', compact('data'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $organization           = $this->organizationManager->getOrganization($this->org_id);
        $organizationIdentifier = $organization->user_identifier;
        $dbRoles                = \DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $roles                  = [];
        $settings               = $this->settingsManager->getSettings(session('org_id'));

        foreach ($dbRoles as $role) {
            $roles[$role->id] = $role->role;
        }

        return view('settings.registerUser', compact('organizationIdentifier', 'roles', 'settings'));
    }


    /**
     * store user into database
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserRequest $request)
    {
        $organization           = $this->organizationManager->getOrganization(session('org_id'));
        $organizationIdentifier = $organization->user_identifier;

        $this->user->first_name = $request->get('first_name');
        $this->user->last_name  = $request->get('last_name');
        $this->user->email      = $request->get('email');
        $this->user->username   = $request->get('username');
        $this->user->org_id     = $this->org_id;
        $this->user->role_id    = $request->get('permission');
        $this->user->password   = bcrypt($request->get('password'));

        $response = ($this->user->save()) ? ['type' => 'success', 'code' => ['created', ['name' => 'User']]] : ['type' => 'danger', 'code' => ['save_failed', ['name' => 'User']]];

//        UserOnBoarding::create(['has_logged_in_once' => false, 'user_id' => $this->user->id]);
        $this->dbLogger->activity("admin.user_created", ['orgId' => $this->org_id, 'userId' => $this->user->id]);

        return redirect()->route('admin.list-users')->withResponse($response);
    }

    /**
     * List all users
     * @return \Illuminate\View\View
     */
    public function listUsers()
    {
        $users    = $this->user->where('org_id', session('org_id'))->get();
        $dbRoles  = \DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $roles    = [];
        $settings = $this->settingsManager->getSettings(session('org_id'));

        foreach ($dbRoles as $role) {
            $roles[$role->id] = $role->role;
        }

        return view('settings.listUsers', compact('users', 'roles', 'settings'));
    }

    /**
     * show users profile
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function viewUserProfile($userId)
    {
        $userProfile = $this->user->findOrNew($userId);

        if (!in_array($userId, $this->organizationManager->getOrganizationUsers($this->org_id))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        return view('admin.viewUserProfile', compact('userProfile'));
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteUser($userId)
    {
        if (!in_array($userId, $this->organizationManager->getOrganizationUsers($this->org_id))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $user     = $this->user->findOrFail($userId);
        $response = ($user->delete($user)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'User']]] : ['type' => 'danger', 'code' => ['delete_failed', ['name' => 'user']]];
        $this->dbLogger->activity("admin.user_deleted", ['orgId' => $this->org_id, 'userId' => $userId]);

        return redirect()->back()->withResponse($response);
    }

    /**
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function resetUserPassword($userId)
    {
        if (!in_array($userId, $this->organizationManager->getOrganizationUsers($this->org_id))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $user = $this->user->findOrFail($userId);

        return view('admin.resetUserPassword', compact('user'));
    }

    /**
     * reset password
     * @param                       $userId
     * @param UpdatePasswordRequest $updatePasswordRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateUserPassword($userId, UpdatePasswordRequest $updatePasswordRequest)
    {
        if (!in_array($userId, $this->organizationManager->getOrganizationUsers($this->org_id))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $input          = Input::all();
        $user           = $this->user->findOrFail($userId);
        $user->password = bcrypt($input['password']);
        $response       = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Password']]] : ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Password']]];

        return redirect()->route('admin.list-users')->withResponse($response);
    }

    /**
     * Update permission of user through ajax
     * @param Request $request
     * @param         $userId
     * @return mixed
     */
    public function updateUserPermission(Request $request, $userId)
    {
        if (!in_array($userId, $this->organizationManager->getOrganizationUsers($this->org_id))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if (!in_array($request->get('permission'), [1, 3, 4])) {
            $input = $request->get('permission');
            $user  = $this->user->findOrFail($userId);

            (!$input) ?: $user->role_id = $input;
            $user->save();

            return 'success';
        } else {
            return 'failed';
        }
    }
}
