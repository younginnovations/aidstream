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
     * @var TransactionModel
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
        $transactionDetails = $this->checkSectorVocabulary($transactionDetails);
        $transaction        = $this->transaction->newInstance(['transaction' => $transactionDetails['transaction'][0]]);
        $activity->transactions()->save($transaction);
    }

    /**
     * update transaction
     * @param $transactionId
     * @param $transactionDetails
     */
    public function update(array $transactionDetails, $transactionId)
    {
        $transactionDetails        = $this->checkSectorVocabulary($transactionDetails);
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
     * @return TransactionModel/bool
     */
    public function deleteBlock($transactionId, $jsonPath)
    {
        $transactionRow = $this->getTransaction($transactionId);
        $transaction    = $transactionRow->transaction;
        $this->removeArrayValue($transaction, $jsonPath);
        $transactionRow->transaction = $transaction;

        if ($transactionRow->save()) {
            return $transactionRow;
        }

        return false;
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

    /*
     * insert sector vocabulary by default "1" if sector code is present
     * @param $transactionDetails
     */
    protected function checkSectorVocabulary($transactionDetails)
    {
        if ($transactionDetails['transaction'][0]['sector'][0]['sector_code'] != "") {
            $transactionDetails['transaction'][0]['sector'][0]['sector_vocabulary'] = "1";
        }

        return $transactionDetails;
    }

    /**
     * Delete specific activity transaction
     * @param TransactionModel $transaction
     * @return bool|null
     * @throws \Exception
     */
    public function deleteTransaction(TransactionModel $transaction)
    {
        return $transaction->delete();
    }

    /**
     * @param $transaction
     * @param $activityId
     * @return \App\Models\Activity\Transaction
     */
    public function createTransaction($transaction, $activityId)
    {
        return $this->transaction->create(
            [
                'transaction' => $transaction,
                'activity_id' => $activityId
            ]
        );
    }
}
