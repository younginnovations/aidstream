<?php namespace App\Tz\Aidstream\Services\Transaction;

use App\Tz\Aidstream\Repositories\Transaction\TransactionRepositoryInterface;
use App\Tz\Aidstream\Traits\TransactionsTrait;
use Exception;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

/**
 * Class TransactionService
 * @package App\Tz\Aidstream\Services\Transaction
 */
class TransactionService
{

    use TransactionsTrait;
    protected $transaction;
    protected $logger;
    protected $databaseManager;

    /**
     * TransactionService constructor.
     * @param TransactionRepositoryInterface $transaction
     * @param LoggerInterface                $logger
     * @param DatabaseManager                $databaseManager
     */
    public function __construct(TransactionRepositoryInterface $transaction, LoggerInterface $logger, DatabaseManager $databaseManager)
    {
        $this->transaction     = $transaction;
        $this->logger          = $logger;
        $this->databaseManager = $databaseManager;
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

    /**
     * @param $projectId
     * @param $transactionType
     * @return array
     */
    public function getTransactions($projectId, $transactionType)
    {
        $transactions = $this->transaction->getTransactionTypeData($projectId, $transactionType);

        $data = [];

        foreach ($transactions as $transaction) {
            $id          = $transaction->id;
            $transaction = json_decode($transaction->transaction, true);

            $transactionDetail['id']           = $id;
            $transactionDetail['reference']    = $transaction['reference'];
            $transactionDetail['date']         = $transaction['transaction_date'][0]['date'];
            $transactionDetail['amount']       = $transaction['value'][0]['amount'];
            $transactionDetail['narrative']    = $transaction['description'][0]['narrative'][0]['narrative'];
//            $transactionDetail['receiver_org'] = $transaction['receiver_organization'][0]['narrative'][0]['narrative'];
            $data[]                            = $transactionDetail;
        }

        return $data;
    }

    public function create(array $transactions)
    {
        $projectId    = $transactions['project_id'];
        $transactions = $this->formatTransactionFormsDataIntoJson($transactions);
        try {
            $this->transaction->create($transactions);
            $this->databaseManager->commit();
            $this->logger->info(
                'Transactions successfully created.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(
                sprintf('Transactions could not created due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return null;

        }

    }
}
