<?php namespace App\Services\Auth;

use App\Http\Controllers\Auth\Traits\ResetsOldPassword;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\User;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Auth;
use Psr\Log\LoggerInterface;

/**
 * Class LoginService
 * @package App\Services\Auth
 */
class LoginService
{
    use ResetsOldPassword;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var null
     */
    protected $attemptingUser = null;

    /**
     * @var array
     */
    protected $credentials = [];

    /**
     * @var
     */
    protected $loggedInUser;

    /**
     * Length of password stored using md5 encryption.
     */
    const MD5_PASSWORD_LENGTH = 32;

    /**
     * LoginService constructor.
     * @param DatabaseManager $databaseManager
     * @param User            $user
     * @param Organization    $organization
     * @param Settings        $settings
     * @param LoggerInterface $logger
     */
    public function __construct(DatabaseManager $databaseManager, User $user, Organization $organization, Settings $settings, LoggerInterface $logger)
    {
        $this->user         = $user;
        $this->organization = $organization;
        $this->settings     = $settings;
        $this->database     = $databaseManager;
        $this->logger       = $logger;
    }

    /**
     * Log the attempting User into the system.
     * @return null
     * @throws Exception
     */
    public function login()
    {
        try {
            $hasRemember = array_key_exists('remember', $this->credentials());

            if (Auth::attempt($this->credentialsOnly(), $hasRemember)) {
                $this->loggedInUser = auth()->user();

                if ($verified = $this->verify()) {
                    $this->storeUserDetailsInSession()
                         ->setVersion();
                } elseif ((false === $verified) || (null === $verified)) {
                    Auth::logout();

                    return $verified;
                }

                return $this->loggedInUser;
            }

            throw new Exception(trans('error.incorrect_credentials'), 401);

        } catch (Exception $exception) {
            if ($exception->getCode() === 401) {
                throw $exception;
            } else {
                $this->logger->error(
                    sprintf('Error due to %s', $exception->getMessage()),
                    [
                        'trace' => $exception->getTraceAsString()
                    ]
                );
            }

            return null;
        }
    }

    /**
     * Filter the type of credentials used for login.
     *
     * @param array $credentials
     * @return $this
     */
    public function filterLoginField(array $credentials)
    {
        $loginValue = $credentials['login'];
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        unset($credentials['login']);

        $this->setCredentials(array_merge($credentials, [$loginField => $loginValue]));

        return $this;
    }

    /**
     * Reset the old password into the md5 encrypted password.
     * @return $this
     * @throws Exception
     */
    public function resetPasswordIfRequired()
    {
        try {
            if ($this->requiresPasswordReset($this->credentials())) {
                $this->resetPassword($this->credentials('password'));
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return $this;
    }

    /**
     * Set the credentials as a property.
     *
     * @param array $credentials
     */
    protected function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Get the credentials for the attempting User.
     *
     * @param null $key
     * @return array|mixed
     */
    protected function credentials($key = null)
    {
        if (!$key) {
            return $this->credentials;
        }

        return $this->credentials[$key];
    }

    /**
     * Check if the user has been verified.
     *
     * @return $this|bool|null
     */
    protected function verify()
    {
        $this->loggedInUser = $this->loggedInUser ? $this->loggedInUser : auth()->user();

        if (!$this->loggedInUser->enabled) {
            return false;
        }

        if (!$this->loggedInUser->verified_status) {
            return null;
        }

        return $this;
    }

    /**
     * Get the credentials only from the credentials property.
     *
     * @return array
     */
    protected function credentialsOnly()
    {
        return array_except($this->credentials, 'remember');
    }

    /**
     * Store the required details in the current User's session.
     *
     * @return $this
     */
    protected function storeUserDetailsInSession()
    {
        session()->put('role_id', $this->loggedInUser->role_id);
        session()->put('org_id', $this->loggedInUser->org_id);
        session()->put('admin_id', $this->loggedInUser->id);
        session()->put('user_permission', $this->loggedInUser->user_permission);
        $settings = $this->settings->where('organization_id', $this->loggedInUser->org_id)->first();
        $version  = (isset($settings)) ? $settings->version : config('app.default_version');
        session()->put('current_version', $version);
        $id = ($settings) ? $settings->organization->system_version_id : false;
        (!$id) ?: session()->put('system_version', $id);

        return $this;
    }

    /**
     * Set the Version details in the User's session.
     */
    protected function setVersion()
    {
        $version  = session('current_version');
        $versions = $this->database->table('versions')->lists('version');

        $versionKey   = array_search($version, $versions);
        $next_version = (end($versions) == $version) ? null : $versions[$versionKey + 1];

        session()->put('next_version', $next_version);
        $version = 'V' . str_replace('.', '', $version);
        session()->put('version', $version);
    }
}
