<?php namespace App\Http\Controllers\Np\Auth;

use App\Http\Controllers\Controller;
use App\Services\RequestManager\Password as PasswordRequestManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Message;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Support\Facades\Password;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var DbLogger
     */
    protected $dbLogger;

    public $redirectTo = '/auth/logout';

    /**
     * Create a new password controller instance.
     * @param Logger   $logger
     * @param DbLogger $dbLogger
     */
    public function __construct(Logger $logger, DbLogger $dbLogger)
    {
        $this->middleware('guest');
        $this->logger   = $logger;
        $this->dbLogger = $dbLogger;
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $this->subject = 'AidStream Account Password Reset';

        try {
            $response = Password::sendResetLink(
                $request->only('email'),
                function (Message $message) {
                    $message->subject($this->getEmailSubject());
                }
            );
            $this->logger->info('Password reset link sent successfully!', ['for' => $request->email]);
            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return redirect()->back()->with('status', trans($response));

                case Password::INVALID_USER:
                    return redirect()->back()->withErrors(['email' => trans($response)]);
            }
        } catch (\Exception $e) {
            $this->logger->error($e, ['email' => $request->email]);

            return redirect()->back()->withErrors(['email' => trans('error.failed_to_send_email')]);
        }
    }

    /**
     * shows create password form
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function showCreatePasswordForm($code)
    {
        $this->resetView = 'np.auth.createPassword';

        return $this->showResetForm(request(), session()->token())->with('verification_code', $code);
    }

    /**
     * shows reset password view
     * @return view
     */
    
    public function showView(){
        return view('np.auth.password');
    }

    /**
     * creates password
     * @param                        $code
     * @param PasswordRequestManager $request
     * @return RedirectResponse
     */
    public function createPassword($code, PasswordRequestManager $request)
    {
        $password       = request('password');
        $user           = User::where('verification_code', $code)->first();
        $user->password = bcrypt($password);
        if ($user->save()) {
            return redirect()->to('/auth/login')->withMessage(trans('success.password_has_been_set'));
        } else {
            return redirect()->to('/auth/login')->withErrors([trans('error.failed_to_save_password')]);
        }
    }

}
