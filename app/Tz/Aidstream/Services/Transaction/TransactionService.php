<?php namespace App\Tz\Aidstream\Services\Transaction;

use App\Tz\Aidstream\Repositories\Transaction\TransactionRepositoryInterface;

/**
 * Class TransactionService
 * @package App\Tz\Aidstream\Services\Transaction
 */
class TransactionService
{

    /**
     * TransactionService constructor.
     * @param TransactionRepositoryInterface $transaction
     */
    public function __construct(TransactionRepositoryInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get transactions on basis of activity id
     * @param $activityId
     * @return mixed
     */
    public function findByActivityId($activityId)
    {
        return $this->transaction->findByActivityId($activityId);
    }
}
