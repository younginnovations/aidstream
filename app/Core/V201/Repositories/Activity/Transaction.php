<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\Transaction as TransactionModel;

/**
 * Class Transaction
 * @package App\Core\V201\Repositories\Activity
 */
class Transaction
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @param TransactionModel $transaction
     */
    function __construct(TransactionModel $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * creates new transaction
     * @param $transactionDetails
     * @param $activity
     */
    public function create(array $transactionDetails, Activity $activity)
    {
        $transaction = $this->transaction->newInstance(['transaction' => $transactionDetails['transaction'][0]]);
        $activity->transactions()->save($transaction);
    }

    /**
     * update transaction
     * @param $transactionId
     * @param $transactionDetails
     */
    public function update(array $transactionDetails, $transactionId)
    {
        $transactions              = $this->getTransaction($transactionId);
        $transactions->transaction = $transactionDetails['transaction'][0];
        $transactions->save();
    }

    /**
     * @param $transactionId
     * @return array
     */
    public function getTransaction($transactionId)
    {
        return $this->transaction->findOrFail($transactionId);
    }
}
