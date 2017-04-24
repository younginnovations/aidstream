<?php namespace App\Services\CsvImporter;


use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\Transaction;
use App\Services\CsvImporter\CsvReader\CsvReader;
use App\Services\CsvImporter\Entities\Activity\Transaction\DataReader\TransactionDataReader;
use App\Services\CsvImporter\Entities\Activity\Transaction\DataWriter\TransactionDataWriter;
use App\Services\CsvImporter\Events\TransactionCsvWasUploaded;
use App\Services\CsvImporter\Queue\TransactionProcessor;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Event;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImportTransactionManager
 * @package App\Services\CsvImporter
 */
class ImportTransactionManager
{
    /**
     * Path to store Uploaded transaction csv.
     */
    const CSV_STORAGE_BASE_PATH = 'csvImporter/tmp/file/transaction';
    /**
     * Base Path to obtain provided template.
     */
    const CSV_TEMPLATE_BASE_PATH = 'Services/CsvImporter/Templates/Activity';
    /**
     * Filename for simple transaction csv.
     */
    const SIMPLE_CSV_TEMPLATE_FILENAME = 'simple_transaction.csv';
    /**
     * Filename for detailed transaction csv.
     */
    const DETAILED_CSV_TEMPLATE_FILENAME = 'detailed_transaction.csv';

    /**
     * Filename for temporary valid json file.
     */
    const VALID_JSON_TEMP_FILENAME = 'valid_temp.json';

    /**
     * Filename for temporary invalid json file.
     */
    const INVALID_JSON_TEMP_FILENAME = 'invalid_temp.json';

    /**
     * Default encoding for csv.
     */
    const DEFAULT_ENCODING = 'UTF-8';

    /**
     * @var TransactionProcessor
     */
    protected $processor;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Contains user id of logged in user.
     * @var
     */
    protected $userId;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var TransactionDataReader
     */
    protected $dataReader;

    /**
     * @var TransactionDataWriter
     */
    protected $dataWriter;
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var Transaction
     */
    protected $transactionRepo;
    /**
     * @var ActivityRepository
     */
    protected $activityRepository;

    /**
     * ImportTransactionManager constructor.
     * @param TransactionProcessor  $processor
     * @param SessionManager        $sessionManager
     * @param ActivityRepository    $activityRepository
     * @param Transaction           $transactionRepo
     * @param Filesystem            $filesystem
     * @param TransactionDataReader $dataReader
     * @param Logger                $logger
     */
    public function __construct(
        TransactionProcessor $processor,
        SessionManager $sessionManager,
        ActivityRepository $activityRepository,
        Transaction $transactionRepo,
        Filesystem $filesystem,
        TransactionDataReader $dataReader,
        Logger $logger
    ) {
        $this->processor          = $processor;
        $this->logger             = $logger;
        $this->userId             = $this->getUserId();
        $this->sessionManager     = $sessionManager;
        $this->dataReader         = $dataReader;
        $this->filesystem         = $filesystem;
        $this->transactionRepo    = $transactionRepo;
        $this->activityRepository = $activityRepository;
    }

    /**
     * Store the uploaded csv in a temp path.
     *
     * @param UploadedFile $file
     * @return bool|null
     */
    public function storeCsvTemporarily(UploadedFile $file)
    {
        try {
            $file->move($this->getStoredCsvPath(), str_replace(' ', '', $file->getClientOriginalName()));

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Returns uploaded csv path.
     *
     * @param null $filename
     * @return string
     */
    protected function getStoredCsvPath($filename = null)
    {
        if ($filename) {
            return sprintf('%s', storage_path(sprintf('%s/%s/%s/%s', self::CSV_STORAGE_BASE_PATH, session('org_id'), $this->userId, $filename)));
        }

        return sprintf('%s', storage_path(sprintf('%s/%s/%s', self::CSV_STORAGE_BASE_PATH, session('org_id'), $this->userId)));
    }

    /**
     * Process the csv file once it has been temporarily stored.
     *
     * @param $filename
     * @param $activityId
     * @return null
     */
    public function process($filename, $activityId)
    {
        try {
            $file = new File($this->getStoredCsvPath($filename));
            $this->processor->pushIntoQueue($file, $filename, $activityId, $this->userId);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Return user id of logged in user.
     *
     * @return mixed
     */
    public function getUserId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }
    }

    /**
     * Store import status in session.
     *
     * @param $filename
     * @return $this
     */
    public function startImport($filename)
    {
        $this->sessionManager->put(['import-status' => 'Processing', 'filename' => $filename]);

        return $this;
    }

    /**
     * Trigger Event once the uploaded csv file is stored.
     *
     * @param $filename
     * @param $activityId
     */
    public function fireCsvUploadEvent($filename, $activityId)
    {
        Event::fire(new TransactionCsvWasUploaded($filename, $activityId));
    }

    /**
     * Return path to download simple transaction template.
     *
     * @param $version
     * @return string
     */
    public function downloadSimpleTransactionTemplate($version)
    {
        return $this->getTemplate($version, self::SIMPLE_CSV_TEMPLATE_FILENAME);
    }

    /**
     * Return path to download detailed transaction template.
     *
     * @param $version
     * @return string
     */
    public function downloadDetailedTransactionTemplate($version)
    {
        return $this->getTemplate($version, self::DETAILED_CSV_TEMPLATE_FILENAME);
    }

    /**
     * Returns path to template.
     *
     * @param $version
     * @param $filename
     * @return string
     */
    protected function getTemplate($version, $filename)
    {
        return sprintf('%s/%s/%s', app_path(self::CSV_TEMPLATE_BASE_PATH), $version, $filename);
    }

    /**
     * Returns processed data stored in the json file.
     *
     * @param $orgId
     * @param $userId
     * @param $activityId
     * @return array|null
     */
    public function getData($orgId, $userId, $activityId)
    {
        try {
            $this->getDataWriterClass($orgId, $userId, $activityId);

            $response  = [];
            $validData = $this->dataReader->getValidJson($orgId, $userId, $activityId);
            (!$validData) ?: $response['validData']['render'] = $this->returnView('valid', $validData);
            $this->dataWriter->storeJson(self::VALID_JSON_TEMP_FILENAME, $validData);

            $invalidData = $this->dataReader->getInValidJson($orgId, $userId, $activityId);
            (!$invalidData) ?: $response['invalidData']['render'] = $this->returnView('invalid', $invalidData);
            $this->dataWriter->storeJson(self::INVALID_JSON_TEMP_FILENAME, $invalidData);

            return $response;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Returns the valid data that are yet to be displayed in the view.
     *
     * @param $orgId
     * @param $userId
     * @param $activityId
     * @return null
     */
    public function getValidData($orgId, $userId, $activityId)
    {
        try {
            $response = [];

            $displayedValidData = $this->dataReader->getJson(self::VALID_JSON_TEMP_FILENAME, $orgId, $userId, $activityId);
            $processedValidData = $this->dataReader->getValidJson($orgId, $userId, $activityId);

            $differenceValidData = array_diff_key($processedValidData, $displayedValidData);
            $displayedValidData  = array_merge($displayedValidData, $differenceValidData);

            if ($differenceValidData) {
                $this->getDataWriterClass($orgId, $userId, $activityId);
                $this->dataWriter->storeJson(self::VALID_JSON_TEMP_FILENAME, $displayedValidData);
                $response['validData']['render'] = $this->returnView('valid', $differenceValidData);
            }

            return $response;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Returns the invalid data that are yet to be displayed in the view.
     *
     * @param $orgId
     * @param $userId
     * @param $activityId
     * @return null
     */
    public function getInvalidData($orgId, $userId, $activityId)
    {
        try {
            $response = [];

            $displayedInvalidData = $this->dataReader->getJson(self::INVALID_JSON_TEMP_FILENAME, $orgId, $userId, $activityId);
            $processedInvalidData = $this->dataReader->getInValidJson($orgId, $userId, $activityId);

            $differenceInvalidData = array_diff_key($processedInvalidData, $displayedInvalidData);
            $displayedInvalidData  = array_merge($displayedInvalidData, $differenceInvalidData);

            if ($differenceInvalidData) {
                $this->getDataWriterClass($orgId, $userId, $activityId);
                $this->dataWriter->storeJson(self::INVALID_JSON_TEMP_FILENAME, $displayedInvalidData);
                $response['invalidData']['render'] = $this->returnView('invalid', $differenceInvalidData);
            }

            return $response;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Returns view to be rendered.
     *
     * @param $view
     * @param $transactions
     * @return string
     */
    public function returnView($view, $transactions)
    {
        return view(sprintf('Activity.csvImporter.transaction.%s', $view), compact('transactions'))->render();
    }

    /**
     * Returns status of the tranasction csv importer.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return array|mixed
     */
    public function checkStatus($organizationId, $userId, $activityId)
    {
        return $this->dataReader->getStatus($organizationId, $userId, $activityId);
    }

    /**
     * Cancel the transaction import process.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     */
    public function cancel($organizationId, $userId, $activityId)
    {
        $this->removeUploadedFile()
             ->clearMappedDirectory($organizationId, $userId, $activityId)
             ->clearSession();
    }

    /**
     * Remove uploaded file from the storage.
     *
     * @return $this
     */
    protected function removeUploadedFile()
    {
        if (is_dir($filePath = $this->getStoredCsvPath())) {
            $this->filesystem->deleteDirectory($this->getStoredCsvPath());
        }

        return $this;
    }

    /**
     * Remove directory that stores processed data.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return $this
     */
    protected function clearMappedDirectory($organizationId, $userId, $activityId)
    {
        if (is_dir($dirPath = $this->dataReader->jsonDataPath($organizationId, $userId, $activityId))) {
            $this->filesystem->deleteDirectory($dirPath);
        }

        return $this;
    }

    /**
     * Clear the session.
     */
    protected function clearSession()
    {
        if (session()->has('import-status')) {
            session()->forget('import-status');
        }

        if (session()->has('filename')) {
            session()->forget('filename');
        }
    }

    /**
     * Store Validated Transactions in the database.
     *
     * @param $transactions
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return bool|null
     */
    public function storeValidatedTransactions($transactions, $organizationId, $userId, $activityId)
    {
        $contents = $this->dataReader->getValidJson($organizationId, $userId, $activityId);

        try {
            foreach ($transactions as $index => $transaction) {
                $validTransactions = $contents[$transaction];
                if (getVal($validTransactions, ['existed'])) {
                    $transactionId = getVal($validTransactions, ['transactionId']);
                    if ($transactionId) {
                        $this->transactionRepo->update($validTransactions, $transactionId);
                    }
                } else {
                    $this->transactionRepo->createTransaction(getVal($validTransactions, ['transaction', 0], []), $activityId);
                }
                $this->activityRepository->resetActivityWorkflow($activityId);
            }

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Transaction CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Instantiate the transaction data writer class.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     */
    protected function getDataWriterClass($organizationId, $userId, $activityId)
    {
        $this->dataWriter = app()->make(TransactionDataWriter::class, [$organizationId, $activityId, $userId]);
    }

    /**
     * Checks if the file is in UTF8Encoding.
     *
     * @param $filename
     * @return bool
     */
    public function isInUTF8Encoding($filename)
    {
        $file = new File($this->getStoredCsvPath($filename));

        if (getEncodingType($file) == self::DEFAULT_ENCODING) {
            return true;
        }

        return false;
    }
}

