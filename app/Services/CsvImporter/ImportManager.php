<?php namespace App\Services\CsvImporter;

use Exception;
use Maatwebsite\Excel\Excel;
use Psr\Log\LoggerInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Session\SessionManager;
use App\Services\CsvImporter\Queue\Processor;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File as FileFacade;
use App\Core\V201\Repositories\Activity\Transaction;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Services\CsvImporter\Events\ActivityCsvWasUploaded;
use App\Core\V201\Repositories\Organization\OrganizationRepository;

/**
 * Class ImportManager
 * @package App\Services\CsvImporter
 */
class ImportManager
{
    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

    /**
     * Directory where the uploaded Csv file is stored temporarily before import.
     */
    const UPLOADED_CSV_STORAGE_PATH = 'csvImporter/tmp/file';

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var ActivityRepository
     */
    protected $activityRepo;

    /**
     * @var OrganizationRepository
     */
    protected $organizationRepo;

    /**
     * @var Transaction
     */
    protected $transactionRepo;

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * File names for the invalid activities.
     * @var array
     */
    protected $invalidActivityFileNames = ['invalid.json', 'invalid-temp.json'];

    /**
     * ImportManager constructor.
     * @param Excel                  $excel
     * @param Processor              $processor
     * @param LoggerInterface        $logger
     * @param SessionManager         $sessionManager
     * @param ActivityRepository     $activityRepo
     * @param OrganizationRepository $organizationRepo
     * @param Transaction            $transactionRepo
     * @param Filesystem             $filesystem
     */
    public function __construct(
        Excel $excel,
        Processor $processor,
        LoggerInterface $logger,
        SessionManager $sessionManager,
        ActivityRepository $activityRepo,
        OrganizationRepository $organizationRepo,
        Transaction $transactionRepo,
        Filesystem $filesystem
    ) {
        $this->excel            = $excel;
        $this->processor        = $processor;
        $this->logger           = $logger;
        $this->sessionManager   = $sessionManager;
        $this->activityRepo     = $activityRepo;
        $this->organizationRepo = $organizationRepo;
        $this->transactionRepo  = $transactionRepo;
        $this->userId           = $this->getUserId();
        $this->filesystem       = $filesystem;
    }

    /**
     * Process the uploaded CSV file.
     * @param $filename
     * @return null
     */
    public function process($filename)
    {
        try {
            $file = new File($this->getStoredCsvPath($filename));

            $activityIdentifiers = $this->getIdentifiers();

            $this->processor->pushIntoQueue($file, $filename, $activityIdentifiers);
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'user'  => auth()->user()->getNameAttribute(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Create Valid activities.
     * @param $activities
     */
    public function create($activities)
    {
        $contents       = json_decode(file_get_contents($this->getFilePath(true)), true);
        $organizationId = $this->sessionManager->get('org_id');

        $organizationIdentifier = getVal(
            $this->organizationRepo->getOrganization($organizationId)->toArray(),
            ['reporting_org', 0, 'reporting_organization_identifier']
        );

        foreach ($activities as $key => $value) {
            $activity                                               = $contents[$value];
            $activity['data']['organization_id']                    = $organizationId;
            $iati_identifier_text                                   = $organizationIdentifier . '-' . getVal($activity, ['data', 'identifier', 'activity_identifier']);
            $activity['data']['identifier']['iati_identifier_text'] = $iati_identifier_text;

            if ($this->isOldActivity($activity)) {
                $oldActivity = $this->activityRepo->getActivityFromIdentifier(getVal($activity, ['data', 'identifier', 'activity_identifier']), $organizationId);
                $oldActivity->transactions()->delete();
                $createdActivity = $this->activityRepo->updateActivityWithIdentifier($oldActivity, getVal($activity, ['data']));
            } else {
                $createdActivity = $this->activityRepo->createActivity(getVal($activity, ['data']));
            }

            if (array_key_exists('transaction', $activity['data'])) {
                $this->createTransaction(getVal($activity['data'], ['transaction'], []), $createdActivity->id);
            }
        }
        $this->activityImportStatus($activities);
    }

    /**
     * Create Transaction of Valid Activities
     * @param $transactions
     * @param $activityId
     */
    public function createTransaction($transactions, $activityId)
    {
        foreach ($transactions as $transaction) {
            $this->transactionRepo->createTransaction($transaction, $activityId);
        }
    }

    /**
     * Check the status of the csv activities being imported.
     * @param $activities
     */
    protected function activityImportStatus($activities)
    {
        if (session('importing') && $this->checkStatusFile()) {
            $this->removeImportedActivity($activities);
        }

        if ($this->checkStatusFile() && is_null(session('importing'))) {
            $this->removeImportDirectory();
        }
    }

    /**
     * Remove the imported activity if the csv is still being processed.
     * @param $checkedActivities
     */
    protected function removeImportedActivity($checkedActivities)
    {
        $validActivities = json_decode(file_get_contents($this->getFilePath(true)), true);
        foreach ($checkedActivities as $key => $activity) {
            unset($validActivities[$key]);
        }

        FileFacade::put($this->getFilePath(true), $validActivities);
    }

    /**
     * Check if the status.json file is present.
     * @return bool
     */
    protected function checkStatusFile()
    {
        return file_exists(storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, 'status.json')));
    }

    /**
     * Remove the user folder containing valid, invalid and status json.
     */
    public function removeImportDirectory()
    {
        $directoryPath = storage_path(sprintf('%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId));
        $this->filesystem->deleteDirectory($directoryPath);
    }

    /**
     * Set the key to specify that import process has started for the current User.
     * @param $filename
     * @return $this
     */
    public function startImport($filename)
    {
        $this->sessionManager->put(['import-status' => 'Processing', 'filename' => $filename]);

        return $this;
    }

    /**
     * Remove the import-status key from the User's current session.
     */
    public function endImport()
    {
        session()->forget('import-status');
        session()->forget('filename');
    }

    /**
     * Get the filepath to the file in which the Csv data is written after processing for import.
     * @param bool $isValid
     * @return string
     */
    public function getFilePath($isValid)
    {
        if ($isValid) {
            return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, self::INVALID_CSV_FILE));
    }

    /**
     * Get the current User's id.
     * @return mixed
     */
    protected function getUserId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }
    }

    /**
     * Clear all invalid activities.
     * @return bool|null
     */
    public function clearInvalidActivities()
    {
        try {
            list($file, $temporaryFile) = [$this->getFilePath(false), $this->getTemporaryFilepath('invalid-temp.json')];

            if (file_exists($file)) {
                unlink($file);
            }

            if (file_exists($temporaryFile)) {
                unlink($temporaryFile);
            }

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error clearing invalid Activities due to [%s]', $exception->getMessage()),
                [
                    'trace'           => $exception->getTraceAsString(),
                    'user_id'         => $this->userId,
                    'organization_id' => session('org_id')
                ]
            );

            return null;
        }
    }

    /**
     * Get the filepath for the temporary files used by the import process.
     * @param $filename
     * @return string
     */
    public function getTemporaryFilepath($filename = null)
    {
        if ($filename) {
            return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, $filename));
        }

        return storage_path(sprintf('%s/%s/%s/', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId));
    }

    /**
     * Set import-status key when the processing is complete.
     */
    protected function completeImport()
    {
        session()->put(['import-status' => 'Complete']);
    }

    /**
     * Get the Csv Import status from the current User's session.
     * @return mixed
     */
    public function getSessionStatus()
    {
        if ($this->checkStatusFile()) {
            $status = file_get_contents($this->getTemporaryFilepath('status.json'));

            if (json_decode($status, true)['status'] == 'Complete') {
                return 'Complete';
            }
        }

        if ($this->sessionManager->has('import-status')) {
            return $this->sessionManager->get('import-status');
        }

        return null;
    }

    /**
     * Get the processed data from the server.
     * @param $filePath
     * @param $temporaryFileName
     * @param $view
     * @return string
     */
    protected function getDataFrom($filePath, $temporaryFileName, $view)
    {
        $activities = json_decode(file_get_contents($filePath), true);
        $path       = $this->getTemporaryFilepath($temporaryFileName);

        $this->fixStagingPermission($this->getTemporaryFilepath());

        FileFacade::put($path, json_encode($activities));

        return view(sprintf('Activity.csvImporter.%s', $view), compact('activities'))->render();
    }

    /**
     * Get processed data from the server.
     * @return array|null
     */
    public function getData()
    {
        try {
            list($validFilepath, $invalidFilepath, $response) = [$this->getFilePath(true), $this->getFilePath(false), []];

            if (file_exists($validFilepath)) {
                $validResponse         = $this->getDataFrom($validFilepath, 'valid-temp.json', 'valid');
                $response['validData'] = ['render' => $validResponse];
            }

            if (file_exists($invalidFilepath)) {
                $invalidResponse         = $this->getDataFrom($invalidFilepath, 'invalid-temp.json', 'invalid');
                $response['invalidData'] = ['render' => $invalidResponse];
            }

            return $response;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error during reading data due to [%s]', $exception->getMessage()),
                [
                    'trace'           => $exception->getTraceAsString(),
                    'user_id'         => $this->userId,
                    'organization_id' => session('org_id')
                ]
            );

            return null;
        }
    }

    /**
     * Store Csv file before import.
     * @param UploadedFile $file
     * @return bool|null
     */
    public function storeCsv(UploadedFile $file)
    {
        try {
            $file->move($this->getStoredCsvPath(), $file->getClientOriginalName());

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Activity CSV file due to [%s]', $exception->getMessage()),
                [
                    'trace'   => $exception->getTraceAsString(),
                    'user_id' => $this->userId
                ]
            );

            return null;
        }
    }

    /**
     * Get the temporary Csv filepath for the uploaded Csv file.
     * @param $filename
     * @return string
     */
    public function getStoredCsvPath($filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s', storage_path(sprintf('%s/%s/%s', self::UPLOADED_CSV_STORAGE_PATH, session('org_id'), $this->userId)), $filename);
        }

        return storage_path(sprintf('%s/%s/%s/', self::UPLOADED_CSV_STORAGE_PATH, session('org_id'), $this->userId));
    }

    /**
     * Reset session values if necessary.
     */
    public function refreshSessionIfRequired()
    {
        if ($this->sessionManager->get('import-status') == 'Complete') {
            $this->sessionManager->forget('filename');
        }
    }

    /**
     * Check if any exceptions have been caught.
     * @return bool
     */
    public function caughtExceptions()
    {
        $filepath = $this->getTemporaryFilepath('header_mismatch.json');

        if (file_exists($filepath)) {
            $contents = json_decode(file_get_contents($filepath), true);
            if (array_key_exists('mismatch', $contents)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Record if the headers have been mismatched during processing.
     */
    public function reportHeaderMismatch()
    {
        $this->sessionManager->put(['header_mismatch' => true]);
    }

    /**
     * Clear keys from the current session.
     * @param array $keys
     */
    public function clearSession(array $keys)
    {
        foreach ($keys as $key) {
            $this->sessionManager->forget($key);
        }
    }

    /**
     * Check if header mismatch has been recorded.
     * @return bool
     */
    public function headersHadBeenMismatched()
    {
        return ($this->sessionManager->has('header_mismatch') && ($this->sessionManager->get('header_mismatch') == true));
    }

    /**
     * Fix file permission while on staging environment
     * @param $path
     */
    protected function fixStagingPermission($path)
    {
        // TODO: Remove this.
        shell_exec(sprintf('chmod 777 -R %s', $path));
    }

    /**
     * Delete a temporary file with the provided filename.
     * @param $filename
     * @return $this
     */
    public function deleteFile($filename)
    {
        if (file_exists($this->getTemporaryFilepath($filename))) {
            unlink($this->getTemporaryFilepath($filename));
        }

        return $this;
    }

    /**
     * Fire Csv Upload event on Csv File upload.
     * @param $filename
     */
    public function fireCsvUploadEvent($filename)
    {
        Event::fire(new ActivityCsvWasUploaded($filename));
    }

    /**
     * Check if the import process is complete.
     * @return bool|string
     */
    public function importIsComplete()
    {
        $filePath = $this->getTemporaryFilepath('status.json');

        if (file_exists($filePath)) {
            $this->fixStagingPermission($filePath);

            $jsonContents = file_get_contents($filePath);
            $contents     = json_decode($jsonContents, true);

            if ($contents['status'] == 'Complete') {
                $this->completeImport();
            }

            return $jsonContents;
        }

        return false;
    }

    /**
     * Check if an old import is on going.
     *
     * @return bool
     */
    protected function hasOldData()
    {
        if ($this->sessionManager->has('import-status') || $this->sessionManager->has('filename')) {
            return true;
        }

        return false;
    }

    /**
     * Clear old import data before another.
     */
    public function clearOldImport()
    {
        $this->removeImportDirectory();

        if ($this->hasOldData()) {
            $this->clearSession(['import-status', 'filename']);
        }
    }

    /**
     * Fetch the processed data.
     *
     * @param $filepath
     * @param $temporaryFilename
     * @return array|mixed
     */
    public function fetchData($filepath, $temporaryFilename)
    {
        $activities = json_decode(file_get_contents($filepath), true);
        $tempPath   = $this->getTemporaryFilepath($temporaryFilename);

        if (file_exists($tempPath)) {
            $old   = json_decode(file_get_contents($tempPath), true);
            $diff  = array_diff_key($activities, $old);
            $total = array_merge($diff, $old);

            FileFacade::put($tempPath, json_encode($total));

            $activities = $diff;
        } else {
            FileFacade::put($tempPath, json_encode($activities));
        }

        return $activities;
    }

    /**
     * Checks if the file is empty or not
     *
     * @param $file
     * @return bool
     */
    public function isCsvFileEmpty($file)
    {
        return (($this->excel->load($file)->get()->count()) > 0) ? false : true;
    }

    /**
     * Provides all the activity identifiers
     *
     * @return array
     */
    protected function getIdentifiers()
    {
        return array_flatten($this->activityRepo->getActivityIdentifiers($this->sessionManager->get('org_id'))->toArray());
    }

    /**
     * Check if the uploaded Activity data is of an exsiting Activity.
     *
     * @param $activity
     * @return bool
     */
    protected function isOldActivity($activity)
    {
        return (getVal($activity, ['existence']) === true);
    }
}

