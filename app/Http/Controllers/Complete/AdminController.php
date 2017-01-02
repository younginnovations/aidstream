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
use App\Services\UserOnBoarding\UserOnBoardingService;
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
     * @var UserOnBoardingService
     */
    protected $userOnBoardingManager;

    /**
     * @param User                  $user
     * @param OrganizationManager   $organizationManager
     * @param DbLogger              $dbLogger
     * @param ActivityManager       $userActivityManager
     * @param SettingsManager       $settingsManager
     * @param UserOnBoardingService $userOnBoardingManager
     */
    function __construct(
        User $user,
        OrganizationManager $organizationManager,
        DbLogger $dbLogger,
        ActivityManager $userActivityManager,
        SettingsManager $settingsManager,
        UserOnBoardingService $userOnBoardingManager
    ) {
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
        $this->org_id                = session('org_id');
        $this->user                  = $user;
        $this->organizationManager   = $organizationManager;
        $this->dbLogger              = $dbLogger;
        $this->userActivityManager   = $userActivityManager;
        $this->settingsManager       = $settingsManager;
        $this->userOnBoardingManager = $userOnBoardingManager;
    }

    /**
     * @param null|string $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId = "all")
    {
        $activity      = $this->userActivityManager->getUserActivities($orgId);
        $organizations = $this->organizationManager->getOrganizations();

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
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
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
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('settings')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->user->first_name = $request->get('first_name');
        $this->user->last_name  = $request->get('last_name');
        $this->user->email      = $request->get('email');
        $this->user->username   = $request->get('username');
        $this->user->org_id     = $this->org_id;
        $this->user->role_id    = $request->get('permission');
        $this->user->password   = bcrypt($request->get('password'));
        $this->user->verified   = true;

        $completedSteps = $this->generateCompletedSteps();
        $response       = ($this->user->save()) ? ['type' => 'success', 'code' => ['user_created', ['name' => $this->user->username]]] : [
            'type' => 'danger',
            'code' => ['save_failed', ['name' => 'User']]
        ];

        $this->userOnBoardingManager->create($this->user->id, $completedSteps);
        $this->dbLogger->activity("admin.user_created", ['orgId' => $this->org_id, 'userId' => $this->user->id]);

        return redirect()->route('admin.list-users')->withResponse($response);
    }

    protected function generateCompletedSteps()
    {
        $completed_steps = [];
        if (auth()->user()->userOnBoarding) {
            $steps           = auth()->user()->userOnBoarding->settings_completed_steps;
            $completed_steps = ($steps) ? $steps : [];
        } else {
            $settings             = auth()->user()->organization->settings;
            $publishingType       = $settings['publishing_type'];
            $registry_info        = $settings['registry_info'];
            $default_field_values = $settings['default_field_values'];
            $default_field_groups = $settings['default_field_groups'];
            $completed_steps      = ($publishingType == "") ? $completed_steps : array_unique(array_merge($completed_steps, [3]));
            $completed_steps      = ($registry_info == "") ? $completed_steps : array_unique(array_merge($completed_steps, [1, 2]));
            $completed_steps      = (is_null($default_field_groups)) ? $completed_steps : array_unique(array_merge($completed_steps, [4]));
            $completed_steps      = (is_null($default_field_values)) ? $completed_steps : array_unique(array_merge($completed_steps, [5]));
        }

        return $completed_steps;

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
        $response = ($user->delete($user)) ? ['type' => 'success', 'code' => ['deleted', ['name' => trans('global.user')]]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => trans('global.user')]]
        ];
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
        $response       = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => trans('global.password')]]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => trans('global.password')]]
        ];

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

        if (in_array($request->get('permission'), [2, 5, 6, 7])) {
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
