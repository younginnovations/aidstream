<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\Transaction as TransactionModel;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param $activityId
     * @return mixed
     */
    public function getTransactionData($activityId)
    {
        return $this->transaction->where('activity_id', $activityId)->get();
    }

    /**
     * deletes data block from transaction
     * @param $transactionId
     * @param $jsonPath
     * @return bool
     */
    public function deleteBlock($transactionId, $jsonPath)
    {
        $transactionRow = $this->getTransaction($transactionId);
        $transaction    = $transactionRow->transaction;
        $this->removeArrayValue($transaction, $jsonPath);
        $transactionRow->transaction = $transaction;

        return $transactionRow->save();
    }

    /*
     * removes value form array with array path
     * */
    protected function removeArrayValue(array &$array, array &$arrayPath)
    {
        if (count($arrayPath) == 1) {
            $array[$arrayPath[0]] = [];
        } else {
            $key = array_shift($arrayPath);
            $this->removeArrayValue($array[$key], $arrayPath);
        }
    }
}
