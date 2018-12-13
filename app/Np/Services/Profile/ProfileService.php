<?php namespace App\Np\Services\Profile;

use App\Np\Contracts\NpOrganizationRepositoryInterface;
use App\Np\Contracts\NpUserRepositoryInterface;
use App\Np\Services\Data\Traits\TransformsData;
use App\Np\Services\Traits\ProvidesLoggerContext;
use App\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ProfileService
 * @package App\Np\Services\Profile
 */
class ProfileService
{
    use ProvidesLoggerContext, TransformsData;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var OrganisationRepositoryInterface
     */
    protected $organisationRepository;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * ProfileService constructor.
     * @param Filesystem                      $fileSystem
     * @param ImageManager                    $imageManager
     * @param UserRepositoryInterface         $userRepository
     * @param OrganisationRepositoryInterface $organisationRepository
     * @param DatabaseManager                 $database
     * @param LoggerInterface                 $logger
     */
    function __construct(
        Filesystem $fileSystem,
        ImageManager $imageManager,
        NpUserRepositoryInterface $userRepository,
        NpOrganizationRepositoryInterface $organisationRepository,
        DatabaseManager $database,
        LoggerInterface $logger
    ) {
        $this->userRepository         = $userRepository;
        $this->organisationRepository = $organisationRepository;
        $this->database               = $database;
        $this->imageManager           = $imageManager;
        $this->fileSystem             = $fileSystem;
        $this->logger                 = $logger;
    }

    /**
     * Provides user
     *
     * @param $userId
     * @return mixed
     */
    public function getUser($userId)
    {
        return $this->userRepository->find($userId);
    }

    /**
     * Store the current users Profile data.
     * @param       $orgId
     * @param       $userId
     * @param array $rawData
     * @param       $version
     * @return array|null
     */
    public function store($orgId, $userId, array $rawData, $version)
    {
        try {
            if (array_key_exists('profile_picture', $rawData)) {
                $file = $rawData['profile_picture'];

                if (!file_exists(public_path('files/users'))) {
                    mkdir(public_path('files/users'), 0777, true);
                }
                $extension = $file->getClientOriginalExtension();

                $fileName = $userId . '.' . $extension;

                $fileUrl = 'files/users/' . $fileName;

                if ($uploaded = $this->uploadFile($fileName, $file)) {
                    $rawData['fileUrl']  = $fileUrl;
                    $rawData['fileName'] = $fileName;
                }
            }

            $profile = $this->transform($this->getMapping($rawData, 'Profile', $version));

            $this->database->beginTransaction();
            $this->userRepository->update($userId, getVal($profile, ['profile'], []));
            $this->organisationRepository->update($orgId, getVal($profile, ['organisation'], []));
            $this->database->commit();

            $this->logger->info('Settings successfully saved.', $this->getContext());

            return $profile;
        } catch (\Exception $exception) {
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
        $image = $this->imageManager->make($file)->fit(
            166,
            166,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        )->encode();

        $filePath = sprintf(public_path('files/users/%s'), $fileName);

        return $this->fileSystem->put($filePath, $image);
    }

    /**
     * Provides settings formModel
     *
     * @param $user
     * @param $organisation
     * @param $version
     * @return array
     */
    public function getFormModel($user, $organisation, $version)
    {
        return $this->transformReverse(
            $this->getMapping(array_merge($organisation, $user), 'Profile', $version)
        );
    }

    /**
     * @param User $user
     * @param      $rawData
     * @return bool|null
     */
    public function storePassword(User $user, $rawData)
    {
        try {
            $currentPassword = $user->getAuthPassword();

            if (Hash::check($rawData['oldPassword'], $currentPassword)) {
                $password['password'] = Hash::make(getVal($rawData, ['newPassword'], null));

                $this->database->beginTransaction();
                $this->userRepository->update($user->id, $password);
                $this->database->commit();

                $this->logger->info('Password successfully changed.', $this->getContext());

                return true;
            }
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error(sprintf('Error due to %s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }

        return null;
    }
}
