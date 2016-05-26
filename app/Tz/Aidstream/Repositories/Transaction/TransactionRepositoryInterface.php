<?php namespace App\Tz\Aidstream\Repositories\Transaction;

/**
 * Interface TransactionRepositoryInterface
 * @package App\Tz\Aidstream\Repositories\Transaction
 */
interface TransactionRepositoryInterface
{

    /**
     * @param $activityId
     * @return mixed
     */
    public function findByActivityId($activityId);
}