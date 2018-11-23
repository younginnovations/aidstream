<?php namespace App\Services;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

/**
 * Class Registration
 * @package App\Services
 */
class NpRegistration
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var OrganizationRepository
     */
    protected $orgRepo;
    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var RegistrationAgencies
     */
    protected $regAgencies;

    protected $onBoardingDisabledSystemVersions = [2];

    /**
     * @param Logger                 $logger
     * @param DatabaseManager        $database
     * @param OrganizationRepository $orgRepo
     * @param RegistrationAgencies   $regAgencies
     */
    public function __construct(Logger $logger, DatabaseManager $database, OrganizationRepository $orgRepo, RegistrationAgencies $regAgencies)
    {
        $this->logger      = $logger;
        $this->orgRepo     = $orgRepo;
        $this->database    = $database;
        $this->regAgencies = $regAgencies;
    }

    /**
     * registers organization info and users
     * @param $orgInfo
     * @param $users
     * @param $systemVersion
     * @return array|bool
     */
    public function register($orgInfo, $users, $systemVersion)
    {
        try {
            $this->database->beginTransaction();
            $orgInfo['secondary_contact'] = $users['secondary_contact'];
            $organization                 = $this->saveOrganization($orgInfo, $systemVersion);
            $users                        = $this->saveUsers($users, $organization);

            if (!in_array($systemVersion, $this->onBoardingDisabledSystemVersions)) {
                foreach ($users as $user) {
                    ($user->userOnBoarding()->create(['has_logged_in_once' => false]));
                }
            }

            $orgInfo['id'] = $organization->id;
            $this->saveRegAgency($orgInfo);
            $this->database->commit();

            return $organization;
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['org_info' => $orgInfo, 'users' => $users]);
        }

        return false;
    }

    /**
     * saves organization and return organization model
     * @param $orgInfo
     * @param $systemVersion
     * @return Organization
     */
    protected function saveOrganization($orgInfo, $systemVersion)
    {
        $orgData      = $this->prepareOrganization($orgInfo, $systemVersion);
        $organization = $this->orgRepo->createOrganization($orgData);
        $organization->orgData()->create(
            [
                'name' => [
                    [
                        "narrative" => $orgInfo['organization_name'], 
                        "language" => ""
                    ],
                    [
                        "narrative" => $orgInfo['organization_name_np'],
                        "language" => "np"
                    ]
                ],
            ]);
        $organization->organizationLocation()->create([
            'district_id' => $orgInfo['organization_district'],
            'municipality_id' => $orgInfo['organization_municipality']
            ]);
        $organization->settings()->create(['version' => '2.02']);

        return $organization;
    }

    /**
     * returns mapped organization data
     * @param $orgInfo
     * @param $systemVersion
     * @return array
     */
    protected function prepareOrganization($orgInfo, $systemVersion)
    {
        $orgData = [];

        $orgData['name']                = $orgInfo['organization_name'];
        $orgData['user_identifier']     = $orgInfo['organization_name_abbr'];
        $orgData['address']             = $orgInfo['organization_address'];
        $orgData['country']             = $orgInfo['country'];
        $orgData['registration_agency'] = $orgInfo['organization_registration_agency'];
        $orgData['registration_number'] = $orgInfo['registration_number'];
        $orgData['reporting_org']       = [
            [
                "reporting_organization_identifier" => sprintf('%s-%s', $orgInfo['organization_registration_agency'], $orgInfo['registration_number']),
                "reporting_organization_type"       => $orgInfo['organization_type'],
                "narrative"                         => [
                    [
                        "narrative" => $orgInfo['organization_name'],
                        "language"  => ""
                    ]
                ]
            ]
        ];
        $orgData['secondary_contact']   = [
            'first_name' => '',
            'last_name'  => '',
            'email'      => $orgInfo['secondary_contact']
        ];
        $orgData['system_version_id']   = $systemVersion;

        return $orgData;
    }

    /**
     * saves organization users
     * @param $users
     * @param $organization Organization
     * @return array
     */
    protected function saveUsers($users, $organization)
    {
        $users['role']     = '1';
        $users['username'] = sprintf('%s_admin', $organization->user_identifier);
        $admin             = $this->prepareUser($users);
        $users             = $this->getUsers(getVal($users, ['user'], []));
        $allUsers          = array_merge([$admin], $users);

        return $organization->users()->createMany($allUsers);
    }

    /**
     * returns prepared users
     * @param $users
     * @return array
     */
    protected function getUsers($users)
    {
        $orgUsers = [];
        foreach ($users as $user) {
            $orgUsers[] = $this->prepareUser($user);
        }

        return $orgUsers;
    }

    /**
     * returns prepared user data
     * @param $user
     * @return array
     */
    protected function prepareUser($user)
    {
        $userData = [];

        $userData['first_name'] = getVal($user, ['first_name']);
        $userData['last_name']  = getVal($user, ['last_name']);
        $userData['email']      = getVal($user, ['email']);
        $userData['username']   = getVal($user, ['username']);
        $password               = getVal($user, ['password']);
        $password               = $password ? bcrypt($password) : '';
        $userData['password']   = $password;
        $userData['role_id']    = getVal($user, ['role']);

        return $userData;
    }

    /**
     * return collection of similar organizations
     * @param $orgName
     * @return Collection
     */
    public function getSimilarOrg($orgName)
    {
        $ignoreList      = ['and', 'of', 'the', 'an', 'a'];
        $orgNameWordList = preg_split('/[\ ]+/', strtolower($orgName));
        $keywords        = array_filter(
            $orgNameWordList,
            function ($value) use ($ignoreList) {
                return !in_array($value, $ignoreList);
            }
        );

        return $this->orgRepo->getSimilarOrg($keywords);
    }

    /**
     * return similar organization array with admin email and organization name
     * @param $similarOrg
     * @return array
     */
    public function prepareSimilarOrg($similarOrg)
    {
        $similarOrgList = [];
        foreach ($similarOrg as $org) {
            $orgName                  = $org->reporting_org[0]['narrative'][0]['narrative'];
            $similarOrgList[$org->id] = $orgName;
        }

        return $similarOrgList;
    }

    /**
     * return similar organization array with admin email and organization name
     * @return array
     */
    public function getOrganizationList()
    {
        $organizations = $this->orgRepo->getOrganizations()->filter(
            function ($organization) {
                return $organization->reporting_org;
            }
        );

        $orgList = [];
        foreach ($organizations as $org) {
            !($orgName = getVal($org->reporting_org, [0, 'narrative', 0, 'narrative'])) ?: $orgList[$org->id] = $orgName;
        }

        return $orgList;
    }

    /**
     * creates registration agency
     * @param $orgInfo
     */
    protected function saveRegAgency($orgInfo)
    {
        $newAgencies = (array) json_decode($orgInfo['new_agencies'], true);
        if (array_key_exists($orgInfo['organization_registration_agency'], $newAgencies)) {
            $regAgency              = $newAgencies[$orgInfo['organization_registration_agency']];
            $regAgency['org_id']    = $orgInfo['id'];
            $regAgency['country']   = $orgInfo['country'];
            $regAgency['moderated'] = true;
            $this->regAgencies->createRegAgency($regAgency);
        }
    }

    /**
     * @param $orgIdentifier
     * @return array
     */
    public function checkOrgIdentifier($orgIdentifier)
    {
        return $this->orgRepo->checkOrgIdentifier($orgIdentifier);
    }

    /**
     * @param $orgId
     * @return array
     */
    public function getOrganization($orgId)
    {
        $organization = $this->orgRepo->getOrganization($orgId);
        $data         = [];
        if ($organization) {
            $data['org_name']    = $organization->reporting_org[0]['narrative'][0]['narrative'];
            $data['org_id']      = $organization->id;
            $admin               = $organization->users->where('role_id', 1)->first();
            $data['admin_name']  = $admin->name;
            $data['admin_email'] = $admin->email;
        }

        return $data;
    }

    /**
     * @param $orgId
     * @return array
     */
    public function hasSecondaryContact($orgId)
    {
        $organization     = $this->orgRepo->getOrganization($orgId);
        $secondaryContact = (array) $organization->secondary_contact;

        return getVal($secondaryContact, ['email']) ? $secondaryContact : [];
    }

    /**
     * @param $orgId
     * @param $email
     * @return array
     */
    public function sendRecoveryEmail($orgId, $email)
    {
        try {
            $organization = $this->orgRepo->getOrganization($orgId);
            $userEmail    = $organization->users->where('role_id', 1)->first()->email;
            session()->put('organization', $organization);

            config(['auth.passwords.users.email' => 'emails.password-secondary']);
            $response = Password::sendResetLink(
                ['email' => $userEmail],
                function (Message $message) use ($email, $organization) {
                    $message->subject(sprintf('Important: Recovery information for %s AidStream account.', $organization->name));
                    $message->to($email);
                }
            );

            return ($response == Password::RESET_LINK_SENT);
        } catch (\Exception $e) {
            $this->logger->error($e, ['email' => $email]);
        }

        return false;
    }
}
