<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\UserActivity;
use App\Services\Organization\OrganizationManager;
use App\User;
use Input;
use Illuminate\Session\SessionManager as Session;
use Illuminate\Contracts\Logging\Log as DbLogger;

/**
 * Class AdminController
 * @package App\Http\Controllers\Complete
 */
class AdminController extends Controller
{
    protected $org_id;
    /**
     * @var Session
     */
    protected $session;

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
     * @param Session             $session
     * @param User                $user
     * @param OrganizationManager $organizationManager
     * @param DbLogger            $dbLogger
     */
    function __construct(Session $session, User $user, OrganizationManager $organizationManager, DbLogger $dbLogger)
    {
        $this->middleware('auth');
        $this->session             = $session;
        $this->org_id              = $this->session->get('org_id');
        $this->user                = $user;
        $this->organizationManager = $organizationManager;
        $this->dbLogger            = $dbLogger;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activity = UserActivity::with('user')->orderBy('id', 'desc')->get();

        return view('admin.activityLog', compact('activity'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $organization           = $this->organizationManager->getOrganization($this->org_id);
        $organizationIdentifier = $organization->user_identifier;

        return view('admin.registerUser', compact('organizationIdentifier'));
    }


    /**
     * store user into database
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserRequest $request)
    {
        $input                       = Input::all();
        $this->user->first_name      = $input['first_name'];
        $this->user->last_name       = $input['last_name'];
        $this->user->email           = $input['email'];
        $this->user->username        = $input['username'];
        $this->user->org_id          = $this->session->get('org_id');
        $this->user->role_id         = 2;
        $this->user->password        = bcrypt($input['password']);
        $this->user->user_permission = isset($input['user_permission']) ? $input['user_permission'] : [];

        $response = ($this->user->save()) ? ['type' => 'success', 'code' => ['created', ['name' => 'User']]] : ['type' => 'danger', 'code' => ['save_failed', ['name' => 'User']]];
        $this->dbLogger->activity("admin.user_created", ['orgId' => $this->org_id, 'userId' => $this->user->id]);

        return redirect()->route('admin.list-users')->withResponse($response);
    }

    /**
     * List all users
     * @return \Illuminate\View\View
     */
    public function listUsers()
    {
        $users = $this->user->getUserByOrgIdAndRoleId();

        return view('admin.listUsers', compact('users'));

    }

    /**
     * show users profile
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function viewUserProfile($userId)
    {
        $userProfile = $this->user->findOrNew($userId);

        return view('admin.viewUserProfile', compact('userProfile'));
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteUser($userId)
    {
        $user     = $this->user->findOrFail($userId);
        $response = ($user->delete($user)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'User']]] : ['type' => 'danger', 'code' => ['delete_failed', ['name' => 'user']]];
        $this->dbLogger->activity("admin.user_deleted", ['orgId' => $this->org_id, 'userId' => $userId]);

        return redirect()->back()->withResponse($response);
    }

    /**
     * @param $userID
     * @return \Illuminate\View\View
     */
    public function resetUserPassword($userID)
    {
        $u = Session::get('user_id');

        $user = $this->user->findOrFail($userID);

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
        $input          = Input::all();
        $user           = $this->user->findOrFail($userId);
        $user->password = bcrypt($input['password']);
        $response       = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Password']]] : ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Password']]];

        return redirect()->route('admin.list-users')->withResponse($response);
    }

    /**
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function editUserPermission($userId)
    {
        $user = $this->user->findOrFail($userId);

        return view('admin.editUserPermission', compact('user'));
    }

    /**
     * update user permission
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateUserPermission($userId)
    {
        $input                 = Input::all();
        $user                  = $this->user->findOrFail($userId);
        $user->user_permission = isset($input['user_permission']) ? $input['user_permission'] : [];
        $response              = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => 'User Permission']]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => 'User Permission']]
        ];
        $this->dbLogger->activity("admin.permission_updated", ['orgId' => $this->org_id, 'userId' => $userId]);

        return redirect()->route('admin.list-users')->withResponse($response);

    }
}
