<?php namespace App\Np\Services\Users;


use App\Np\Contracts\NpUserRepositoryInterface;
use App\Np\Repositories\Users\NpUserRepository;
use App\Np\Services\Traits\ProvidesLoggerContext;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Psr\Log\LoggerInterface;

/**
 * Class UserService
 * @package App\Np\Services\Users
 */
class UserService
{
    use ProvidesLoggerContext;

    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param LoggerInterface         $logger
     * @param Mailer                  $mailer
     */
    public function __construct(NpUserRepositoryInterface $userRepository, LoggerInterface $logger, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->logger         = $logger;
        $this->mailer         = $mailer;
    }

    /**
     * Save the user details.
     *
     * @param array $user
     * @return boolean
     */
    public function save(array $user)
    {
        try {
            $user = $this->userRepository->save($user);

            $this->logger->info('User has been created successfully.', $this->getContext());

            return $user;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return false;
        }
    }

    /**
     * Return all the users present in the organisation.
     *
     * @param $orgId
     * @return mixed
     */
    public function all($orgId)
    {
        return $this->userRepository->all($orgId);
    }

    /**
     * Returns the specific user.
     *
     * @param $userId
     * @return mixed
     */
    public function find($userId)
    {
        return $this->userRepository->find($userId);
    }

    /**
     * Delete the user.
     *
     * @param $userId
     * @return bool
     */
    public function delete($userId)
    {
        try {
            $user = $this->userRepository->delete($userId);

            $this->logger->info('User has been deleted successfully.', $this->getContext());

            return $user;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return false;
        }
    }

    /**
     * Update the permission of the user.
     *
     * @param $userId
     * @param $permission
     * @return bool
     */
    public function updatePermission($userId, $permission)
    {
        try {
            $this->userRepository->update($userId, ['role_id' => $permission]);

            $this->logger->info('User Permission has been updated successfully.', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return false;
        }
    }

    /**
     * Update the username of all users of the organisation when userIdentifier is changed.
     *
     * @param $newUserIdentifier
     * @param $oldUserIdentifier
     * @param $orgId
     * @return bool
     */
    public function updateUsername($newUserIdentifier, $oldUserIdentifier, $orgId)
    {
        try {
            $users = $this->all($orgId);

            foreach ($users as $user) {
                $oldUsername = $user->username;
                $nameOnly    = substr($oldUsername, strlen($this->removeUnderScoreIfPresent($oldUserIdentifier)) + 1);
                $this->userRepository->update($user->id, ['username' => $newUserIdentifier . '_' . $this->removeUnderScoreIfPresent($nameOnly)]);
            }

            $this->logger->info('Username has been updated successfully.', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return false;
        }
    }

    /**
     *  Removes underscore from the string if present.
     *
     * @param $name
     * @return mixed
     */
    public function removeUnderScoreIfPresent($name)
    {
        if (preg_match('/\_/', $name)) {
            return preg_replace('/\_/', '', $name, 1);
        };

        return $name;
    }

    /**
     * Sent email to the user if username is changed.
     *
     * @param $orgId
     */
    public function notifyUsernameChanged($orgId)
    {
        $users   = $this->all($orgId);
        $orgName = auth()->user()->organization->name;

        foreach ($users as $user) {
            $view            = 'lite.emails.usernameChanged';
            $callback        = function ($message) use ($user) {
                $message->subject('AidStream Account Username changed');
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($user->email);
            };
            $data            = $user->toArray();
            $data['orgName'] = $orgName;
            $this->mailer->send($view, $data, $callback);
        }
    }
}

