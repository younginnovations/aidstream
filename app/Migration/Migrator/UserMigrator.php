<?php namespace App\Migration\Migrator;

use App\Migration\Entities\User;
use App\User as UserModel;
use App\Migration\Migrator\Contract\MigratorContract;

/**
 * Class UserMigrator
 * @package App\Migration\Migrator
 */
class UserMigrator implements MigratorContract
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * UserMigrator constructor.
     * @param User      $user
     * @param UserModel $userModel
     */
    public function __construct(User $user, UserModel $userModel)
    {
        $this->user      = $user;
        $this->userModel = $userModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate()
    {
//        $userIds = $this->user->allUserIds();

        $oldUserData = $this->user->getData();

        foreach ($oldUserData as $userData) {
            $newUser = $this->userModel->newInstance($userData);

            if (!$newUser->save()) {
                return 'Error during User table migration.';
            }
        }

        return 'Users table migrated.';
    }
}
