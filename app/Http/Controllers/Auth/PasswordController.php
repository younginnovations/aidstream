<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Mail\Message;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Support\Facades\Password;
use Psr\Log\LoggerInterface as Logger;

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

            return redirect()->back()->withErrors(['email' => 'Failed to send email.']);
        }
    }

}
