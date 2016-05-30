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

    /**
     * @param $activityId
     * @param $transactionType
     * @return mixed
     */
    public function getTransactionTypeData($activityId, $transactionType);

    /**
     * @param $transactions
     * @return mixed
     */
    public function create($transactions);
}