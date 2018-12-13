<?php namespace App\Services;

use App\Models\Organization\Organization;
use App\Models\Settings;
use App\User;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Class Verification
 * @package App\Services
 */
class Verification
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Mailer
     */
    protected $mailer;
    /**
     * @var Organization
     */
    protected $organization;

    /**
     * Verification constructor.
     * @param Mailer       $mailer
     * @param User         $user
     * @param Organization $organization
     */
    public function __construct(Mailer $mailer, User $user, Organization $organization)
    {
        $this->mailer       = $mailer;
        $this->user         = $user;
        $this->organization = $organization;
    }

    /**
     * sends email to all users
     * @param $user User
     */
    public function sendVerificationEmail($user)
    {
        $user           = $this->generateVerificationCode($user);
        $method         = [
            1 => 'getAdminComponents',
            2 => 'getUserComponents',
            5 => 'getUserComponents',
            6 => 'getUserComponents',
            7 => 'getUserComponents'
        ];
        $data           = $user->toArray();
        $emailComponent = $this->{$method[$user->role_id]}($data);

        $this->mailer->send($emailComponent['view'], $data, $emailComponent['callback']);
    }

    /**
     * return admin verification components
     * @param $data
     * @return array
     */
    protected function getAdminComponents($data)
    {
        $callback = function ($message) use ($data) {
            $message->subject('Welcome to AidStream');
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        return ['view' => 'emails.admin', 'callback' => $callback];
    }

    /**
     * return user verification components
     * @param $data
     * @return array
     */
    protected function getUserComponents($data)
    {
        $callback = function ($message) use ($data) {
            $message->subject('Welcome to AidStream');
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        return ['view' => 'emails.user', 'callback' => $callback];
    }

    /**
     * generates verification code for user and returns user
     * @param User $user
     * @return User
     */
    protected function generateVerificationCode($user)
    {
        $user->verification_code       = hash_hmac('sha256', str_random(40), config('app.key'));
        $user->verification_created_at = date('Y-m-d H:i:s', time());
        $user->save();

        return $user;
    }

    /**
     * verifies user
     * @param $code
     * @return RedirectResponse
     */
    public function verifyUser($code)
    {
        $user = $this->user->where('verification_code', $code)->first();
        if (!$user) {
            $message = 'Your verification process has already been completed.';
        } elseif ($user->update(['verified' => true])) {
            $method = [
                1 => 'verifyAdmin',
                2 => 'verifyOrgUser',
                5 => 'verifyOrgUser',
                6 => 'verifyOrgUser',
                7 => 'verifyOrgUser'
            ];

            return $this->{$method[$user->role_id]}($user);
        } else {
            $message = 'Failed to verify your account.';
        }

        return redirect()->to('/auth/login')->withErrors([$message]);
    }

    /**
     * verifies admin
     * @param User $user
     * @return RedirectResponse
     */
    protected function verifyAdmin(User $user)
    {
        $users = $this->user->join('role', 'users.role_id', '=', 'role.id')
                            ->select(['*', 'users.id as id'])
                            ->where('org_id', $user->org_id)
                            ->whereNotNull('permissions')
                            ->orderBy('users.id', 'asc')
                            ->get();
        $this->sendVerificationEmailToUsers($users);

        if($user->organization->system_version_id !== 4){
            $this->sendSecondaryVerificationEmail($user->organization);
        }

        $message = view('verification.admin', compact('users', 'user'));

        return redirect()->to('/auth/login')->withVerificationMessage($message->__toString());
    }

    /**
     * verifies secondary user
     * @param $code
     * @return RedirectResponse
     */
    public function verifySecondary($code)
    {
        $organization = $this->organization->whereRaw("secondary_contact ->> 'verification_code' = ?", [$code])->first();
        if ($organization) {
            $user                            = $organization->secondary_contact;
            $user['verified']                = true;
            $organization->secondary_contact = $user;
            $message                         = view('verification.secondary');
            $message                         = $message->__toString();
            $organization->save();
        } else {
            $message = 'Invalid Verification Code.';
        }

        return redirect()->to('/')->withVerificationMessage($message);
    }

    /**
     * verifies organization user
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function verifyOrgUser(User $user)
    {
        return redirect()->route('show-create-password', $user->verification_code);
    }

    /**
     * saves Registry Information
     * @param                 $code
     * @param                 $registryInfo
     * @param SettingsManager $settingsManager
     * @return bool
     */
    public function saveRegistryInfo($code, $registryInfo, SettingsManager $settingsManager)
    {
        try {
            $publishingInfo =
                [
                    "publisher_id"        => $registryInfo['publisher_id'],
                    "api_id"              => $registryInfo['api_id'],
                    "publish_files"       => "no",
                    "publishing"          => "unsegmented",
                    "publisher_id_status" => "Incorrect",
                    "api_id_status"       => "Incorrect"
                ];
            $settings       = $settingsManager->getSettingsByCode($code);
            $settingsManager->savePublishingInfo($publishingInfo, $settings);
            $this->login($code);

            return true;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * sends verification email to secondary contact
     * @param $organization
     */
    public function sendSecondaryVerificationEmail($organization)
    {
        $secondary                            = $organization->secondary_contact;
        $secondary['verification_code']       = hash_hmac('sha256', str_random(40), config('app.key'));
        $secondary['verification_created_at'] = date('Y-m-d H:i:s', time());
        $secondary['verified']                = false;
        $organization->secondary_contact      = $secondary;
        $organization->save();

        $data             = $secondary;
        $data['admin']    = $organization->users->where('role_id', 1)->first()->toArray();
        $orgName          = getVal($organization->reporting_org,[0,'narrative',0,'narrative']);
        $data['org_name'] = $orgName;

        $callback         = function ($message) use ($data) {
            $message->subject(sprintf('%s is now live on AidStream', $data['org_name']));
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        if($data['email']){
            $this->mailer->send('emails.secondary', $data, $callback);
        }
    }

    /**
     * sends verification email to organization users
     * @param $users
     */
    protected function sendVerificationEmailToUsers($users)
    {
        foreach ($users as $user) {
            $this->sendVerificationEmail($user);
        }
    }

    /**
     * Login user after verification code.
     * @param $code
     */
    protected function login($code)
    {
        $user                    = $this->user->where('verification_code', $code)->first();
        $organization            = $user->organization;
        $user->verification_code = null;
        $user->save();

        session(['org_id' => $organization->id]);
        session(['first_login' => true]);
        session(['role_id' => $user->role_id]);
        $this->setVersion($user);

        auth()->loginUsingId($user->id);
    }

    /**
     * Set version of the IATI in session.
     * @param $user
     */
    protected function setVersion($user)
    {
        $settings = Settings::where('organization_id', $user->org_id)->first();
        $version  = (isset($settings)) ? $settings->version : config('app.default_version');
        Session::put('current_version', $version);
        Session::put('system_version', $settings->organization->system_version_id);
        $version     = session('current_version');
        $versions_db = DB::table('versions')->get();
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
}
