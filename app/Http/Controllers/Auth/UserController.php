<?php namespace App\Http\Controllers\Auth;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UsernameRequest;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\File;
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

    /**
     * @param Organization   $organization
     * @param User           $user
     * @param SessionManager $sessionManager
     */
    function __construct(Organization $organization, User $user, SessionManager $sessionManager)
    {
        $this->organization   = $organization;
        $this->user           = $user;
        $this->sessionManager = $sessionManager;
        $this->orgId          = $this->sessionManager->get('org_id');
    }

    /**
     * view user profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewProfile()
    {
        $organization = $this->organization->getOrganization();

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

        $response = ($user->save() && $organization->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Username']]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => 'Username']]
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

        if (!Hash::check($input['old_password'], $user->password)) {
            $response = ['type' => 'danger', 'code' => ['password_mismatch', ['name' => 'Password']]];

            return redirect()->back()->withResponse($response);
        }

        $user->password = bcrypt($input['password']);
        $response       = ($user->save()) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Password']]] : ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Password']]];

        return redirect('user/profile')->withResponse($response);
    }


    /**
     * edit profile interface
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile($userId)
    {
        $user         = $this->user->findOrFail($userId);
        $organization = $this->organization->findOrFail($this->orgId);
        $baseForm     = new BaseForm();
        $timeZone     = $baseForm->getCodeList('TimeZone', 'Activity', false);

        return view('User.editProfile', compact('user', 'organization', 'timeZone'));
    }

    /**
     * updates profile
     * @param                $userId
     * @param ProfileRequest $profileRequest
     * @return mixed
     */
    public function updateProfile($userId, ProfileRequest $profileRequest)
    {
        $input        = $profileRequest->all();
        $user         = $this->updateUserProfile($input, $userId);
        $organization = $this->updateOrganizationProfile($input);
        $response     = ($user && $organization) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Profile']]] : [
            'type' => 'danger',
            'code' => ['update_failed', ['name' => 'Profile']]
        ];

        return redirect('user/profile')->withResponse($response);
    }

    /**
     * update user profile
     * @param $input
     * @param $userId
     * @return mixed
     */
    protected function updateUserProfile($input, $userId)
    {
        $user               = $this->user->findOrFail($userId);
        $user->first_name   = $input['first_name'];
        $user->last_name    = $input['last_name'];
        $user->email        = $input['email'];
        $timeZone           = explode(' : ', $input['time_zone']);
        $user->time_zone_id = $timeZone[0];
        $user->time_zone    = $timeZone[1];

        return $user->save();
    }

    /**
     * update organization profile
     * @param $input
     * @return mixed
     */
    protected function updateOrganizationProfile($input)
    {
        $organization = $this->organization->findOrFail($this->orgId);
        $file         = Input::file('organization_logo');

        if ($file) {
            $fileUrl  = url('files/logos/' . $this->orgId . '.' . $file->getClientOriginalExtension());
            $fileName = $this->orgId . '.' . $file->getClientOriginalExtension();
            $image    = Image::make(File::get($file))->resize(150, 150)->encode();
            Storage::put('logos/' . $fileName, $image);
            $organization->logo_url = $fileUrl;
            $organization->logo     = $fileName;
        }

        $organization->name             = $input['organization_name'];
        $organization->address          = $input['organization_address'];
        $organization->country          = $input['country'];
        $organization->organization_url = $input['organization_url'];
        $organization->telephone        = $input['organization_telephone'];
        $organization->twitter          = $input['organization_twitter'];
        $organization->disqus_comments  = array_key_exists('disqus_comments', $input) ? $input['disqus_comments'] : null;

        return $organization->save();
    }
}
