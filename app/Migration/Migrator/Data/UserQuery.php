<?php namespace App\Migration\Migrator\Data;


use App\Migration\Elements\UserPermission;

/**
 * Class UserQuery
 * @package App\Migration\Migrator\Data
 */
class UserQuery extends Query
{
    /**
     * @var UserPermission
     */
    protected $userPermission;

    /**
     * UserQuery constructor.
     * @param UserPermission $userPermission
     */
    public function __construct(UserPermission $userPermission)
    {
        $this->userPermission = $userPermission;
    }

    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $this->initDBConnection();

        $data = [];

        foreach ($accountIds as $accountId) {
            $users = $this->getUsersFor($accountId);

            foreach ($users as $user) {
                $data[] = $this->getDataFor($user);
            }
        }

        return $data;
    }

    /**
     * Get Users for a specific account.
     * @param $accountId
     * @return mixed
     */
    public function getUsersFor($accountId)
    {
        return $this->connection->table('user')->select('*')->where('account_id', '=', $accountId)->get();
    }

    /**
     * Get Users data.
     * @param $user
     * @return array
     */
    public function getDataFor($user)
    {
        $userId              = $user->user_id;
        $accountId           = $user->account_id;
        $newPermissionFormat = null;

        $profile = $this->connection->table('profile')
                                    ->select('*')
                                    ->where('user_id', '=', $userId)
                                    ->first();

        $userPermissionData = $this->connection->table('user_permission')
                                               ->select('object')
                                               ->where('user_id', '=', $userId)
                                               ->first();

        if (!empty($userPermissionData)) {
            $arrayUserPermission = (array) unserialize($userPermissionData->object);
            $newPermissionFormat = $this->userPermission->format($arrayUserPermission);
        }

        $newUser = array(
            'id'              => $userId,
            'first_name'      => $profile->first_name,
            'last_name'       => $profile->last_name,
            'email'           => $user->email,
            'username'        => $user->user_name,
            'password'        => $user->password,
            'role_id'         => $user->role_id,         //here continue
            'user_permission' => $newPermissionFormat,
            'org_id'          => $accountId
        );

        return $newUser;
    }
}
