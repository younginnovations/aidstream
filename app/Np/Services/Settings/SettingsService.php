<?php namespace App\Np\Services\Settings;

use App\Np\Contracts\NpSettingsRepositoryInterface;
use App\Np\Contracts\NpOrganisationRepositoryInterface;
use App\Np\Repositories\Settings\NpSettingsRepository;
use App\Np\Services\Data\Traits\TransformsData;
use App\Np\Services\Traits\ProvidesLoggerContext;
use App\Np\Services\Users\UserService;
use App\Models\UserOnBoarding;
use App\User;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SettingsService
 * @package App\Np\Services\Settings
 */
class SettingsService
{

    use ProvidesLoggerContext, TransformsData;

    /**
     * @var OrganisationRepositoryInterface
     */
    protected $organisationRepository;

    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * SettingsService constructor.
     * @param NpOrganisationRepositoryInterface $organisationRepository
     * @param NpSettingsRepositoryInterface     $settingsRepository
     * @param UserService                     $userService
     * @param DatabaseManager                 $database
     * @param LoggerInterface                 $logger
     */
    public function __construct(
        NpOrganisationRepositoryInterface $organisationRepository,
        NpSettingsRepositoryInterface $settingsRepository,
        UserService $userService,
        DatabaseManager $database,
        LoggerInterface $logger
    ) {
        $this->organisationRepository = $organisationRepository;
        $this->settingsRepository     = $settingsRepository;
        $this->database               = $database;
        $this->logger                 = $logger;
        $this->userService            = $userService;
    }

    /**
     * Provides settings formModel
     *
     * @param $orgId
     * @param $version
     * @return array
     */
    public function getSettingsModel($orgId, $version)
    {
        $organisation = json_decode($this->organisationRepository->find($orgId), true);
        $settings     = json_decode($this->settingsRepository->getSettingsWithOrgId($orgId), true);

        $model = array_merge($organisation, $settings);

        $filteredModel = $this->transformReverse($this->getMapping($model, 'Settings', $version));

        return $filteredModel;
    }

    /**
     * Stores settings data
     *
     * @param $orgId
     * @param $rawData
     * @param $version
     * @return array|true
     */
    public function store($orgId, array $rawData, $version)
    {
        try {
            if (array_key_exists('organisation_logo', $rawData)) {
                $file = $rawData['organisation_logo'];

                if (!file_exists(public_path('files/logos'))) {
                    mkdir(public_path('files/logos'));
                }
                $extension = $file->getClientOriginalExtension();

                $fileName = $orgId . '.' . $extension;

                $fileUrl = 'files/logos/' . $fileName;

                if ($uploaded = $this->uploadFile($fileName, $file)) {
                    $rawData['fileUrl']  = $fileUrl;
                    $rawData['fileName'] = $fileName;
                }
            }

            $settings = $this->transform($this->getMapping($rawData, 'Settings', $version));

            $this->database->beginTransaction();
            $isUsernameUpdated = $this->updateUserName($rawData);
            $this->settingsRepository->saveWithOrgId($orgId, getVal($settings, ['settings'], []));
            $this->organisationRepository->update($orgId, getVal($settings, ['organisation'], []));
            $this->database->commit();

            $this->logger->info('Settings successfully saved.', $this->getContext());

            if ($isUsernameUpdated) {
                return config('users.usernameUpdatedCode');
            }

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(sprintf('Error due to %s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Uploads file
     *
     * @param              $fileName
     * @param UploadedFile $file
     * @return mixed
     */
    protected function uploadFile($fileName, UploadedFile $file)
    {
        $image = Image::make(File::get($file))->fit(
            166,
            166,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        )->encode();

        return Storage::put('logos/' . $fileName, $image);
    }

    /**
     * Finds the settings of the given organisation.
     *
     * @param $orgId
     * @return \App\Models\Settings
     */
    public function find($orgId)
    {
        return $this->settingsRepository->find($orgId);
    }

    /**
     * Upgrade AidStream to Core.
     *
     * @param $organizationId
     * @return bool|null
     */
    public function upgradeSystemVersion($organizationId)
    {
        try {
            $organization = $this->organisationRepository->find($organizationId);
            $users        = $organization->users;

            $this->database->beginTransaction();
            $this->organisationRepository->upgradeSystem($organization);
            $this->enableOnBoardingFor($users);
            $this->database->commit();

            $this->logger->info('System successfully upgraded.');

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(sprintf('System could not be upgraded due to %s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Enable user on boarding for the users of the Organization on upgrade.
     *
     * @param Collection $users
     * @return Collection
     */
    protected function enableOnBoardingFor(Collection $users)
    {
        return $users->each(
            function ($user) {
                $completedSteps = $this->completedStepsFor($user);
                if ($onBoarding = $user->userOnBoarding) {
                    $this->updateUserOnBoarding($onBoarding, $completedSteps);
                } else {
                    $this->createOnBoarding($user, $completedSteps);
                }
            }
        );
    }

    /**
     * Returns the completed steps for onboarding before upgrading.
     *
     * @param User $user
     * @return array
     */
    protected function completedStepsFor(User $user)
    {
        $settings       = $user->organization->settings;
        $completedSteps = [];

        (getVal((array) $settings->registry_info, [0, 'publisher_id']) == "" && getVal((array) $settings, [0, 'api_id']) == "") ?: array_push($completedSteps, 1);
        (getVal((array) $settings->registry_info, [0, 'publish_files']) == "") ?: array_push($completedSteps, 3);
        ($settings->publishing_type == "") ?: array_push($completedSteps, 2);

        return $completedSteps;
    }

    /**
     * Update the username when organisation abbreviation is changed.
     *
     * @param $rawData
     * @return bool
     */
    protected function updateUserName($rawData)
    {
        $newOrgAbbreviation = getVal($rawData, ['organisationNameAbbreviation']);
        $organisation       = $this->organisationRepository->find(session('org_id'))->toArray();
        $oldOrgAbbreviation = getVal($organisation, ['user_identifier']);

        if (strtolower($newOrgAbbreviation) !== strtolower($oldOrgAbbreviation)) {
            $this->userService->updateUsername($newOrgAbbreviation, $oldOrgAbbreviation, session('org_id'));

            return true;
        }

        return false;
    }

    /**
     * Get the Organization with the specific organizationId.
     *
     * @param $organizationId
     * @return \App\Models\Organization\Organization
     */
    public function getOrganization($organizationId)
    {
        return $this->organisationRepository->find($organizationId);
    }

    /**
     * Update User Onboarding when upgrading into Core AidStream.
     *
     * @param       $onBoarding
     * @param array $completedSteps
     */
    protected function updateUserOnBoarding($onBoarding, array $completedSteps)
    {
        $onBoarding->has_logged_in_once       = false;
        $onBoarding->completed_tour           = false;
        $onBoarding->settings_completed_steps = $completedSteps;
        $onBoarding->display_hints            = true;
        $onBoarding->save();
    }

    /**
     * Create User Onboarding when upgrading into Core AidStream.
     * @param $user
     * @param $completedSteps
     */
    protected function createOnBoarding($user, $completedSteps)
    {
        $userOnBoarding = app()->make(UserOnBoarding::class);
        $userOnBoarding->fill(['has_logged_in_once' => false, 'settings_complete_steps' => $completedSteps]);
        $userOnBoarding->user()->associate($user);

        $userOnBoarding->save();
    }
}

