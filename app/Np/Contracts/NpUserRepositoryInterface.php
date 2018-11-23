<?php namespace App\Np\Contracts;


/**
 * Interface UserRepositoryInterface
 * @package App\Np\Contracts
 */
interface NpUserRepositoryInterface
{
    /**
     * @param $userId
     * @return mixed
     */
    public function find($userId);

    /**
     * @param array $user
     * @return mixed
     */
    public function save(array $user);

    /**
     * @param $orgId
     * @return mixed
     */
    public function all($orgId);

    /**
     * @param $userId
     * @return mixed
     */
    public function delete($userId);

    /**
     * @param       $userId
     * @param array $parameters
     * @return mixed
     */
    public function update($userId, array $parameters);
}
