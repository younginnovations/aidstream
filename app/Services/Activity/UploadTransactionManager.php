<?php namespace App\Services\Activity;

use App\Core\Version;
use App;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface as Logger;
use Exception;

/**
 * Class UploadTransactionManager
 * @package App\Services\Activity
 */
class UploadTransactionManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Version
     */
    protected $version;
    protected $transactionRepo;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Version $version
     * @param Guard   $auth
     * @param Log     $dbLogger
     * @param Logger  $logger
     */
    public function __construct(Version $version, Guard $auth, Log $dbLogger, Logger $logger)
    {
        $this->auth                  = $auth;
        $this->version               = $version;
        $this->dbLogger              = $dbLogger;
        $this->logger                = $logger;
        $this->transactionRepo       = $version->getActivityElement()->getTransaction()->getRepository();
        $this->uploadTransactionRepo = $version->getActivityElement()->getUploadTransaction()->getRepository();
    }

    /**
     * prepared the data and save uploaded transaction
     * @param          $transactionCsv
     * @param Activity $activity
     * @return bool
     */
    public function save($transactionCsv, Activity $activity)
    {
        $excel           = $this->version->getExcel();
        $transactionRows = $excel->load($transactionCsv)->get();

        foreach ($transactionRows as $transactionRow) {
            $transactionDetails[] = $this->uploadTransactionRepo->formatFromExcelRow($transactionRow);
        }

        $references = $this->uploadTransactionRepo->getTransactionReferences($activity->id);
        try {
            foreach ($transactionDetails as $transactionDetail) {
                $transactionReference = $transactionDetail['reference'];
                (isset($references[$transactionReference])) ? $this->uploadTransactionRepo->update($transactionDetail, $references[$transactionReference]) : $this->uploadTransactionRepo->upload(
                    $transactionDetail,
                    $activity
                );
            }
            $this->logger->info("Transactions Uploaded for activity with id :" . $activity->id);
            $this->dbLogger->activity("activity.transaction_uploaded", ['activity_id' => $activity->id]);

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Transaction could not be uploaded due to %s', $exception->getMessage()),
                [
                    'transaction' => $transactionDetails,
                    'trace'       => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
