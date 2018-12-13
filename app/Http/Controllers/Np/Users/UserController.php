<?php namespace App\Http\Controllers\Np\Users;

use App\Http\Controllers\Controller;
use App\Np\Services\FormCreator\Users;
use App\Np\Services\Users\UserService;
use App\Np\Services\Validation\ValidationService;
use App\Models\Role;
use App\Services\Organization\OrganizationManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

/**
 * Class UserController
 * @package App\Http\Controllers\Np\Users
 */
class UserController extends Controller
{
    /**
     * @var Users
     */
    protected $userForm;
    /**
     * @var ValidationService
     */
    protected $validation;

    /**
     *
     */
    const ENTITY = 'Users';
    /**
     * @var UserService
     */
    protected $userService;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * UserController constructor.
     * @param UserService         $userService
     * @param Users               $userForm
     * @param ValidationService   $validationService
     * @param OrganizationManager $organizationManager
     */
    public function __construct(UserService $userService, Users $userForm, ValidationService $validationService, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->middleware('auth.admin', ['except' => ['index']]);
        $this->userForm            = $userForm;
        $this->validation          = $validationService;
        $this->userService         = $userService;
        $this->organizationManager = $organizationManager;
    }

    /**
     * Returns the view that displays list of users present in the organisation.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users   = $this->userService->all(session('org_id'));
        $dbRoles = DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $roles   = [];

        foreach ($dbRoles as $role) {
            $roles[$role->id] = $role->role;
        }

        return view('np.users.index', compact('users', 'roles'));
    }

    /**
     * Return form to create the user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $form                   = $this->userForm->form();
        $organizationIdentifier = auth()->user()->organization->user_identifier;

        return view('np.users.create', compact('form', 'organizationIdentifier'));
    }

    /**
     * Stores user details in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$this->validation->passes($request->all(), self::ENTITY, session('version'))) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($request->all());
        }

        if ($this->userService->save($request->except(['_token', 'password_confirmation']))) {
            return redirect()->route('np.users.index')->withResponse(['type' => 'success', 'code' => ['created', ['name' => trans('lite/global.user')]]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.user')]]]);
    }


    /**
     * Delete the user.
     *
     * @param $userId
     */
    public function destroy($userId)
    {
        if (!in_array($userId, $this->organizationManager->getOrganizationUsers(session('org_id')))) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if ($this->userService->delete($userId)) {
            return redirect()->back()->withResponse(['type' => 'success', 'code' => ['deleted', ['name' => trans('lite/global.user')]]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['delete_failed', ['name' => trans('lite/global.user')]]]);
    }

    /**
     * Updates the permission of the user from AJAX Request.
     *
     * @param $id
     * @return bool|string
     */
    public function updatePermission($id)
    {
        if (!in_array($id, $this->organizationManager->getOrganizationUsers(session('org_id')))) {
            return false;
        }

        $permission           = Input::get('permission');
        $availablePermissions = Role::lists('id')->toArray();

        if (in_array($permission, $availablePermissions)) {
            $this->userService->updatePermission($id, $permission);

            return 'success';
        }

        return false;
    }

    /**
     * Sent email to the users of the organisation when user identifier is changed.
     *
     * @return mixed
     */
    public function notifyUsernameChanged()
    {
        $this->userService->notifyUsernameChanged(session('org_id'));
        $response = ['type' => 'success', 'code' => ['sent', ['name' => 'Emails']]];

        return redirect()->route('np.settings.edit')->withResponse($response);
    }
}
