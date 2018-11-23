<?php namespace App\Http\Controllers\Auth;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UsernameRequest;
use App\Models\Organization\Organization;
use App\Services\Organization\OrganizationManager;
use App\Services\UserManager;
use App\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class UserController
 * @package App\Http\Controllers\Auth
 */
class UserController extends Controller
{
    /**
     * @var Organization
     */
    protected $organization;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    protected $orgId;
    protected $userManager;
    protected $organizationManager;

    /**
     * @param Organization        $organization
     * @param User                $user
     * @param SessionManager      $sessionManager
     * @param UserManager         $userManager
     * @param OrganizationManager $organizationManager
     */
    function __construct(Organization $organization, User $user, SessionManager $sessionManager, UserManager $userManager, OrganizationManager $organizationManager)
    {
        $this->organization        = $organization;
        $this->user                = $user;
        $this->sessionManager      = $sessionManager;
        $this->orgId               = $this->sessionManager->get('org_id');
        $this->userManager         = $userManager;
        $this->organizationManager = $organizationManager;
    }

    /**
     * view user profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewProfile()
    {
        $organization = $this->organizationManager->getOrganization($this->orgId);

        return view('User.profile', compact('user', 'organization'));
    }

    /**
     * interface for changing username
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeUserName($userId)
    {
        $user = $this->user->findOrFail($userId);

        if (Gate::denies('isValidUser', $user)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $user);

        return view('User.changeUserName', compact('user'));
    }

    /**
     * updates username
     * @param                 $userId
     * @param UsernameRequest $usernameRequest
     * @param DatabaseManager $database
     * @return mixed
     */
    public function updateUsername($userId, UsernameRequest $usernameRequest, DatabaseManager $database)
    {
        $input        = $usernameRequest->all();
        $user         = $this->user->findOrFail($userId);
        $organization = $this->organization->findOrFail($this->orgId);

        $database->beginTransaction();
        $organization->user_identifier = $input['organization_user_identifier'];
        $user->username                = $input['username'];
        $user->save();
        $organization->save();
        $database->commit();

        $response = ($user->save() && $organization->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => trans('user.username')]]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => trans('user.username')]]
        ];

        return redirect('user/profile')->withResponse($response);
    }

    /**
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function resetUserPassword($userId)
    {
        $user = $this->user->findOrFail($userId);

        if (Gate::denies('isValidUser', $user)) {
            return redirect()->route('user.profile')->withResponse($this->getNoPrivilegesMessage());
        }

        return view('User.updateUserPassword', compact('user'));
    }

    /**
     * reset password
     * @param                       $userId
     * @param ChangePasswordRequest $changePasswordRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateUserPassword($userId, ChangePasswordRequest $changePasswordRequest)
    {
        $input = Input::all();
        $user  = $this->user->findOrFail($userId);

        if (Gate::denies('isValidUser', $user)) {
            return redirect()->route('user.profile')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!Hash::check($input['old_password'], $user->password)) {
            $response = ['type' => 'danger', 'code' => ['password_mismatch', ['name' => trans('user.password')]]];

            return redirect()->back()->withResponse($response);
        }

        $user->password = bcrypt($input['password']);
        $response       = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => trans('user.password')]]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => trans('user.password')]]
        ];

        return redirect('user/profile')->withResponse($response);
    }


    /**
     * edit profile interface
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile($userId)
    {
        $user_details    = $this->userManager->getUser($userId);
        $user_permission = Auth::user()->role->role;

        if (Gate::denies('isValidUser', $user_details)) {
            return redirect()->route('user.profile')->withResponse($this->getNoPrivilegesMessage());
        }

        $organization = $this->organizationManager->getOrganization(session('org_id'));

        $user     = $this->userManager->getUserDetails($userId);
        $baseForm = new BaseForm();
        $timeZone = $baseForm->getCodeList('TimeZone', 'Activity', false);

        return view('User.editProfile', compact('user', 'organization', 'timeZone', 'user_permission'));
    }

    /**
     * updates profile
     * @param                $userId
     * @param ProfileRequest $profileRequest
     * @return mixed
     */
    public function updateProfile($userId, ProfileRequest $profileRequest)
    {
        $input = $profileRequest->all();
        $user  = $this->user->findOrFail($userId);

        if (Gate::denies('isValidUser', $user)) {
            return redirect()->route('user.profile')->withResponse($this->getNoPrivilegesMessage());
        }
        $user = $this->userManager->updateUserProfile($input);

        $response = ($user) ? ['type' => 'success', 'code' => ['updated', ['name' => trans('user.profile')]]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => 'Profile']]
        ];

        return redirect('user/profile')->withResponse($response);
    }

}
