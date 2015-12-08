<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
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

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
                'email'                        => 'required|email|max:255|unique:users',
                'username'                     => 'required|max:255|unique:users',
                'password'                     => 'required|confirmed|min:6',
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
        $organization = Organization::create(
            [
                'name'            => $data['organization_name'],
                'address'         => $data['organization_address'],
                'user_identifier' => $data['organization_user_identifier'],
            ]
        );

        return User::create(
            [
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'username'   => $data['username'],
                'password'   => bcrypt($data['password']),
                'org_id'     => $organization->id,
                'role_id'    => 1,
            ]
        );
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
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

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            Session::put('role_id', $user->role_id);
            Session::put('org_id', $user->org_id);
            $settings       = Settings::where('organization_id', $user->org_id)->first();
            $settings_check = isset($settings);
            $version        = ($settings_check) ? $settings->version : config('app.default_version');
            $version        = 'V' . str_replace('.', '', $version);
            Session::put('version', $version);
            !(Session::get('url.intended') == url()) ?: Session::forget('url');
            $redirectPath = ($user->role_id == 1 || $user->role_id == 2) ? '/activity' : '/admin/dashboard';

            return redirect()->intended($redirectPath);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($field, 'remember'))
            ->withErrors(
                [
                    $field => $this->getFailedLoginMessage(),
                ]
            );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $this->create($request->all());

        return redirect('/auth/login')->withMessage('Your account has been successfully created. Please log in to continue.');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
