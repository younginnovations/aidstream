<?php namespace App\Tz\Aidstream\Repositories\Transaction;

use App\Tz\Aidstream\Models\Transaction;

/**
 * Class TransactionRepository
 * @package App\Tz\Aidstream\Repositories\Transaction
 */
class TransactionRepository implements TransactionRepositoryInterface
{

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * TransactionRepository constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get transaction data via activity id
     * @param $activityId
     * @return mixed
     */
    public function findByActivityId($activityId)
    {
        return $this->transaction->where('activity_id', '=', $activityId)->get();
    }
}
