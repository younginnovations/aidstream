<?php namespace App\Services\Activity;

use App\Core\Version;
use App;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class TransactionManager
 * @package App\Services\Activity
 */
class TransactionManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var DbLogger
     */
    protected $dbLogger;
    /**
     * @var Version
     */
    protected $version;
    protected $transactionRepo;

    /**
     * @param Version  $version
     * @param Guard    $auth
     * @param DbLogger $dbLogger
     * @param Logger   $logger
     */
    public function __construct(Version $version, Guard $auth, DbLogger $dbLogger, Logger $logger)
    {
        $this->auth            = $auth;
        $this->logger          = $logger;
        $this->dbLogger        = $dbLogger;
        $this->transactionRepo = $version->getActivityElement()->getTransaction()->getRepository();
    }

    /**
     * saves the transaction details
     * @param array    $transactionDetails
     * @param Activity $activity
     * @param null     $transactionId
     * @return bool
     */
    public function save(array $transactionDetails, Activity $activity, $transactionId = null)
    {
        try {
            ($transactionId) ? $this->transactionRepo->update($transactionDetails, $transactionId) : $this->transactionRepo->create($transactionDetails, $activity);
            $this->logger->info(($transactionId) ? 'Activity Transaction Updated' : 'Activity Transaction added');
            $dbLoggerData = ['activity_id' => $activity->id];
            (!$transactionId) ?: $dbLoggerData['transaction_id'] = $transactionId;
            $this->dbLogger->activity(($transactionId) ? "activity.transaction_updated" : "activity.transaction_added", $dbLoggerData);

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['transaction' => $transactionDetails]);
        }

        return false;
    }

    /**
     * get transaction detail
     * @param $transactionId
     * @return mixed
     */
    public function getTransaction($transactionId)
    {
        return $this->transactionRepo->getTransaction($transactionId);
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getTransactions($activityId)
    {
        return $this->transactionRepo->getTransactionData($activityId);
    }

    /**
     * deletes data block from transaction
     * @param $transactionId
     * @param $jsonPath
     * @return bool
     */
    public function deleteBlock($transactionId, $jsonPath)
    {
        try {
            $jsonPath        = explode('/', $jsonPath);
            $transactionData = $this->transactionRepo->deleteBlock($transactionId, $jsonPath);
            if (!$transactionData) {
                return false;
            }
            $this->dbLogger->activity(
                "activity.transaction_block_removed",
                ['activity_id' => $transactionData->activity_id, 'transaction_id' => $transactionId, 'json_path' => implode('->', $jsonPath)]
            );

            return true;
        } catch (Exception $exception) {
            dd($exception);
            $this->logger->error($exception, ['transactionId' => $transactionId]);
        }

        return false;
    }
}
