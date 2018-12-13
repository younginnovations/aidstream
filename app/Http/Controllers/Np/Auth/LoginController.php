<?php namespace App\Http\Controllers\Np\Auth;


use App\Exceptions\Aidstream\InvalidUserException;
use App\Http\Controllers\Auth\Traits\RedirectsUsersToCorrectVersion;
use App\Http\Controllers\Auth\Traits\ResetsOldPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Auth\LoginService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    use AuthenticatesAndRegistersUsers, RedirectsUsersToCorrectVersion;

    /**
     * @var LoginService
     */
    protected $loginService;

    protected $host;

    /**
     * 'Np' System Version Id.
     */
    const NP_VERSION_ID = 4;

    /**
     * LoginController constructor.
     * @param LoginService $loginService
     */
    public function __construct(LoginService $loginService)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->loginService = $loginService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        $view = property_exists($this, 'loginView')
            ? $this->loginView : 'auth.authenticate';

        if (view()->exists($view)) {
            return view($view);
        }

        return view('np.auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validateUserLogin($request);
        try {
            $user = $this->loginService->filterLoginField($request->except('_token'))
                                       ->resetPasswordIfRequired()
                                       ->login();

            if ($user) {
                if ($user->isMunicipalityAdmin()) {
                    return redirect()->intended(config('app.municipality_dashboard'));
                }

                if ($this->userIsRegisteredForSpecificVersion($user)) {
                    return $this->redirectToCorrectVersion($user);
                }

                $redirectPath = ($user->isSuperAdmin() || $user->isGroupAdmin())
                    ? config('app.super_admin_dashboard')
                    : config('app.admin_dashboard');

                $intendedUrl = session()->get('url.intended');

                $redirectPath = $this->userOnBoardingRedirectPath($user, $redirectPath);

                !(($user->role_id == 3 || $user->role_id == 4) && strpos($intendedUrl, '/admin') === false) ?: $intendedUrl = url('/');
                !($intendedUrl == url('/')) ?: session()->set('url.intended', $redirectPath);

                return redirect()->intended($redirectPath);
            } elseif (false === $user) {
                return redirect('/auth/login')->withErrors(trans('error.account_disabled'));
            } elseif (null === $user) {
                return redirect('/auth/login')->withErrors(
                    trans('error.account_not_verified')
                );
            }

            $login = $request->input('login');
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            return redirect($this->loginPath())
                ->withInput($request->only($field, 'remember'))
                ->withErrors(
                    [
                        $field => $this->getFailedLoginMessage(),
                    ]
                );
        } catch (Exception $exception) {
            return redirect($this->loginPath())->withErrors($exception->getMessage());
        }
    }

    /**
     * Validate the User's login information.
     *
     * @param $request
     */
    protected function validateUserLogin($request)
    {
        list($rules, $messages) = [
            ['login' => 'required', 'password' => 'required'],
            ['login.required' => 'Username/Email is required.', 'password.required' => 'Password is required.']
        ];

        $this->validate($request, $rules, $messages);
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
                (count($completedSteps) == 5) ?: session()->put('first_login', true);
            } else {
                session()->put('first_login', true);
                $redirectPath = 'welcome';
            }
        } elseif ($user->role_id == 3 || $user->role_id == 4) {
            $redirectPath = config('app.super_admin_dashboard');
        }

        return $redirectPath;
    }

    /**
     * @return string
     */
    public function loginPath()
    {
        return '/auth/login';
    }
}
