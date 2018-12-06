<?php namespace App\Http\Controllers\Np\Auth;

use App\Core\EmailQueue;
use App\Core\Form\BaseForm;
use App\Http\Controllers\Auth\Traits\RedirectsUsersToCorrectVersion;
use App\Http\Controllers\Auth\Traits\ResetsOldPassword;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ResetsOldPassword, RedirectsUsersToCorrectVersion;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var null
     */
    protected $attemptingUser = null;

    /**
     * Length of password stored using md5 encryption.
     */
    const MD5_PASSWORD_LENGTH = 32;

    /**
     * Create a new authentication controller instance.
     * @param DatabaseManager $database
     * @param Organization    $organization
     * @param User            $user
     * @param Settings        $settings
     */
    public function __construct(DatabaseManager $database, Organization $organization, User $user, Settings $settings)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->database     = $database;
        $this->organization = $organization;
        $this->user         = $user;
        $this->settings     = $settings;
    }

    /**
     * @return string
     */
    public function loginPath()
    {
        return '/auth/login';
    }

    /**
     * createGet a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'organization_name'            => 'required|max:255',
                'organization_address'         => 'required|max:255',
                'organization_user_identifier' => 'required|max:255|unique:organizations,user_identifier',
                'first_name'                   => 'required|max:255',
                'last_name'                    => 'required|max:255',
                'country'                      => 'required',
                'email'                        => 'required|email|max:255|unique:users',
                'username'                     => 'required|max:255|unique:users',
                'password'                     => 'required|confirmed|min:6'
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        $organization = $this->organization->create(
            [
                'name'            => $data['organization_name'],
                'address'         => $data['organization_address'],
                'country'         => $data['country'],
                'user_identifier' => $data['organization_user_identifier'],
            ]
        );

        $latestVersion = $this->database->table('versions')->select('version')->where('id', '=', $this->database->table('versions')->max('id'))->get();
        $this->settings->create(
            [
                'version'         => $latestVersion[0]->version,
                'organization_id' => $organization->id
            ]
        );

        return $this->user->create(
            [
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'username'   => $data['username'],
                'password'   => bcrypt($data['password']),
                'org_id'     => $organization->id,
                'role_id'    => 1
            ]
        );
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request|\Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'login'    => 'required',
                'password' => 'required',
            ]
        );

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->merge([$field => $login]);

        $credentials = $request->only($field, 'password');

        try {
            if ($this->requiresPasswordReset($credentials)) {
                $this->resetPassword($credentials['password']);
            }

            if (Auth::attempt($credentials, $request->has('remember'))) {
                $user = Auth::user();

                if (($response = getVal($this->verify($user), ['status'])) !== true) {
                    Auth::logout();

                    return redirect()->to('auth/login')->withErrors($response);
                }

                $this->storeDetailsInSession($user)
                     ->setVersions();

                if ($this->userIsRegisteredForSpecificVersion($user)) {
                    return $this->redirectToCorrectVersion($user);
                }

                $redirectPath = ($user->isSuperAdmin() || $user->isGroupAdmin())
                    ? config('app.super_admin_dashboard')
                    : config('app.admin_dashboard');

                $intendedUrl = Session::get('url.intended');

                $redirectPath = $this->userOnBoardingRedirectPath($user, $redirectPath);

                !(($user->role_id == 3 || $user->role_id == 4) && strpos($intendedUrl, '/admin') === false) ?: $intendedUrl = url('/');
                !($intendedUrl == url('/')) ?: Session::set('url.intended', $redirectPath);

                return redirect()->intended($redirectPath);
            }

            return redirect($this->loginPath())
                ->withInput($request->only($field, 'remember'))
                ->withErrors(
                    [
                        $field => $this->getFailedLoginMessage(),
                    ]
                );
        } catch (\Exception $exception) {
            Auth::logout();

            return redirect('/auth/login')->withErrors(trans('error.unable_to_login'));
        }
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $baseForm  = new BaseForm();
        $countries = $baseForm->getCodeList('Country', 'Organization');

        return view('np.auth.register', compact('countries'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request|\Illuminate\Http\Request $request
     * @param EmailQueue                       $emailQueue
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function postRegister(Request $request, EmailQueue $emailQueue)
    {
        $input     = $request->all();
        $validator = $this->validator($input);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $registered = $this->create($input);

        if ($registered) {
            $emailQueue->sendRegistrationMail($input);
        }

        return redirect($this->loginPath())->withMessage(trans('success.thank_you_for_registering'));
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        if (auth()->check() && Auth::user()->userOnBoarding) {
            Auth::user()->userOnBoarding->has_logged_in_once = true;
            Auth::user()->userOnBoarding->save();
        }
        auth()->user()->last_login = date('m-d-Y H:i:s');
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function checkUserIdentifier(Request $request)
    {
        $userIdentifier = $request->get('userIdentifier');
        if ($this->organization->where('user_identifier', $userIdentifier)->count() == 0) {
            $response = ['status' => 'success', 'message' => trans('success.organisation_name_abbreviation')];
        } else {
            $response = ['status' => 'danger', 'message' => trans('error.organisation_name_abbreviation_taken')];
            if ($request->get('validation')) {
                return '';
            }
        }

        return $response;
    }

    /**
     *  Verify the status of the user.
     * @param $user
     * @return array
     */
    protected function verify($user)
    {
        if (!$user->enabled) {
            return ['status' => trans('error.account_disabled')];
        }

        if (!$user->verified_status) {
            return ['status' => trans('error.account_not_verified')];
        }

        return ['status' => true];
    }

    /**
     *  Store role id, org id, admin id and version in session.
     * @param $user
     * @return $this
     */
    protected function storeDetailsInSession($user)
    {
        Session::put('role_id', $user->role_id);
        Session::put('org_id', $user->org_id);
        Session::put('admin_id', $user->id);
        $settings = Settings::where('organization_id', $user->org_id)->first();
        $version  = (isset($settings)) ? $settings->version : config('app.default_version');
        Session::put('current_version', $version);
        $id = ($settings) ? $settings->organization->system_version_id : false;
        (!$id) ?: Session::put('system_version', $id);

        return $this;
    }

    /**
     * Set Version
     */
    protected function setVersions()
    {
        $version     = session('current_version');
        $versions_db = $this->database->table('versions')->get();
        $versions    = [];
        foreach ($versions_db as $ver) {
            $versions[] = $ver->version;
        }
        $versionKey   = array_search($version, $versions);
        $next_version = (end($versions) == $version) ? null : $versions[$versionKey + 1];

        Session::put('next_version', $next_version);
        $version = 'V' . str_replace('.', '', $version);
        Session::put('version', $version);
    }

    /**
     * Set Redirect path according to the status of the userOnBoarding.
     * @param $user
     * @param $redirectPath
     * @return mixed|string
     */
    protected function userOnBoardingRedirectPath($user, $redirectPath)
    {
        if ($user->userOnBoarding) {
            if ($user->userOnBoarding->has_logged_in_once) {
                $redirectPath   = ($user->role_id == 3 || $user->role_id == 4) ? config('app.super_admin_dashboard') : config('app.admin_dashboard');
                $completedSteps = (array) $user->userOnBoarding->settings_completed_steps;
                (count($completedSteps) == 5) ?: Session::put('first_login', true);
            } else {
                Session::put('first_login', true);
                $redirectPath = 'welcome';
            }
        } elseif ($user->role_id == 3 || $user->role_id == 4) {
            $redirectPath = config('app.super_admin_dashboard');
        }

        return $redirectPath;
    }
}
