<?php namespace App\Migration\Entities;


use App\Migration\MigrateUser;
use App\Migration\Migrator\Data\UserQuery;

/**
 * Class User
 * @package App\Migration\Entities
 */
class User
{
    /**
     * @var MigrateUser
     */
    protected $migrateUser;

    /**
     * @var array
     */
    protected $data = [];

    protected $userQuery;

    /**
     * User constructor.
     * @param MigrateUser $migrateUser
     */
    public function __construct(MigrateUser $migrateUser, UserQuery $userQuery)
    {
        $this->migrateUser = $migrateUser;
        $this->userQuery   = $userQuery;
    }

    /**
     * Gets Users data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->userQuery->executeFor($accountIds);
    }
}
