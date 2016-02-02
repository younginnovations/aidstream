<?php namespace App\Migration\Entities;


use App\Migration\MigrateUser;

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

    /**
     * User constructor.
     * @param MigrateUser $migrateUser
     */
    public function __construct(MigrateUser $migrateUser)
    {
        $this->migrateUser = $migrateUser;
    }

    /**
     * Gets Users data from old database.
     * @return array
     */
    public function getData()
    {
        $userId = ['96', '256', '255', '254']; // fetch all User Ids/Users.

        foreach ($userId as $id) {
            $this->data[] = $this->migrateUser->userDataFetch($id);
        }

        return $this->data;
    }
}
