<?php namespace App\Tz\Aidstream\Services\Transaction;

use App\Tz\Aidstream\Repositories\Transaction\TransactionRepositoryInterface;
use App\Tz\Aidstream\Services\Project\ProjectService;
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

    /**
     * @var TransactionRepositoryInterface
     */
    protected $transaction;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var DatabaseManager
     */
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

        foreach ($transactions as $key => $transaction) {
            $id               = $transaction->id;
            $transaction      = json_decode($transaction->transaction, true);
            $data[]           = $transaction;
            $data[$key]['id'] = $id;
        }

        return $data;
    }

    /**
     * Create a new Transaction.
     * @param array $transactions
     * @return bool|null
     */
    public function create(array $transactions)
    {
        try {
            $this->databaseManager->beginTransaction();
            $transactions = $this->saveValueDate($transactions);
            $this->transaction->create($transactions);
            $this->resetWorkflow($transactions['project_id']);

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

    /**
     * Get Transaction data.
     * @param      $projectId
     * @param      $transactionType
     * @param bool $decode
     * @return array|mixed
     */
    public function getTransactionsData($projectId, $transactionType, $decode = false)
    {
        $transactions = $this->transaction->getTransactionTypeData($projectId, $transactionType);

        if (!$decode) {
            return $transactions;
        }

        return $this->decode($transactions);
    }

    /**
     * Decode JSON data for Transactions for edit view.
     * @param array $transactions
     * @return array
     */
    protected function decode(array $transactions)
    {
        $decodedTransactions = [];

        foreach ($transactions as $key => $transaction) {
            $decodedTransactions[$key]['transaction'] = json_decode($transaction->transaction, true);
            $decodedTransactions[$key]['id']          = $transaction->id;
        }

        return $decodedTransactions;
    }

    /**
     * Update an existing Transaction.
     * @param $transactions
     * @return bool|null
     */
    public function update($transactions)
    {
        try {
            $this->databaseManager->beginTransaction();
            $transactions = $this->saveValueDate($transactions);
            $this->transaction->update($transactions);
            $this->databaseManager->commit();
            $this->logger->info(
                'Transactions successfully updated.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(
                sprintf('Transactions could not updated due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }

    }

    /**
     * Reset Project workflow.
     * @param $projectId
     */
    protected function resetWorkflow($projectId)
    {
        $project = app()->make(ProjectService::class)->find($projectId);

        $project->activity_workflow = 0;

        $project->save();
    }

    /**
     * Find specific transaction
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->transaction->find($id);
    }

    /**
     * Delete specific transaction
     * @param $transactions
     * @return bool|null
     */
    public function destroy($transactions)
    {
        try {
            $this->databaseManager->beginTransaction();

            foreach ($transactions as $transaction) {
                $this->transaction->destroy($transaction);
            }

            $this->databaseManager->commit();
            $this->logger->info(
                'Transactions successfully deleted.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(
                sprintf('Transactions could not deleted due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return null;
        }
    }

    /**
     * delete single transaction
     * @param $transactionId
     * @return bool|null
     */
    public function destroyTransaction($transactionId)
    {
        try {
            $this->databaseManager->beginTransaction();

            $this->transaction->destroy($transactionId);

            $this->databaseManager->commit();
            $this->logger->info(
                'Transactions successfully deleted.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(
                sprintf('Transactions could not deleted due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return null;
        }
    }

    /**
     * Save value date equivalent to transaction date
     * @param $transactions
     * @return mixed
     */
    public function saveValueDate($transactions)
    {
        foreach ($transactions['transaction'] as $key => $transaction) {
            $transactions['transaction'][$key]['value'][0]['date'] = $transaction['transaction_date'][0]['date'];
        }

        return $transactions;
    }

    public function findByType($projectId, $transactionType)
    {
        return $this->transaction->findByType($projectId, $transactionType);
    }

    public function getTransactionsSum($projects)
    {
        $transactions      = [];
        $incomingFundCount = 0;
        $disbursementCount = 0;
        $expenditureCount  = 0;
        foreach ($projects as $project) {
            $transactions[] = $this->findByActivityId($project->id);
        }

        foreach ($transactions as $transactionData) {
            foreach ($transactionData as $transaction) {
                if ($transaction->transaction['transaction_type'][0]['transaction_type_code'] == "1") {
                    $incomingFundCount += $transaction->transaction['value'][0]['amount'];
                } elseif ($transaction->transaction['transaction_type'][0]['transaction_type_code'] == "3") {
                    $disbursementCount += $transaction->transaction['value'][0]['amount'];
                } elseif ($transaction->transaction['transaction_type'][0]['transaction_type_code'] == "4") {
                    $expenditureCount += $transaction->transaction['value'][0]['amount'];
                }
            }

        }

        return [
            'incoming_fund' => $incomingFundCount,
            'disbursement'  => $disbursementCount,
            'expenditure'   => $expenditureCount
        ];
    }
}
