<?php namespace App\Services\CsvImporter;

use Exception;
use Maatwebsite\Excel\Excel;
use Psr\Log\LoggerInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File as FileFacade;
use App\Services\CsvImporter\Queue\ResultProcessor;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\CsvImporter\Events\ResultCsvWasUploaded;
use App\Core\V201\Repositories\Activity\Result as ResultRepository;
use App\Core\V201\Repositories\Organization\OrganizationRepository;

/**
 * Class ImportResultManager
 * @package App\Services\CsvImporter
 */
class ImportResultManager
{
    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp/result';

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
    const UPLOADED_CSV_STORAGE_PATH = 'csvImporter/tmp/result/file';

    /**
     *
     */
    const DEFAULT_ENCODING = 'UTF-8';

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var ResultProcessor
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
     * @var ResultRepository
     */
    protected $resultRepo;

    /**
     * @var OrganizationRepository
     */
    protected $organizationRepo;

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
     * System Version
     *
     * @var String
     */
    protected $version;

    /**
     * File names for the invalid results.
     * @var array
     */
    protected $invalidResultFileNames = ['invalid.json', 'invalid-temp.json'];

    /**
     * ImportManager constructor.
     * @param Excel                  $excel
     * @param ResultProcessor        $processor
     * @param LoggerInterface        $logger
     * @param SessionManager         $sessionManager
     * @param ResultRepository       $resultRepo
     * @param OrganizationRepository $organizationRepo
     * @param Filesystem             $filesystem
     */
    public function __construct(
        Excel $excel,
        ResultProcessor $processor,
        LoggerInterface $logger,
        SessionManager $sessionManager,
        ResultRepository $resultRepo,
        OrganizationRepository $organizationRepo,
        Filesystem $filesystem
    ) {
        $this->excel            = $excel;
        $this->processor        = $processor;
        $this->logger           = $logger;
        $this->sessionManager   = $sessionManager;
        $this->resultRepo       = $resultRepo;
        $this->organizationRepo = $organizationRepo;
        $this->userId           = $this->getUserId();
        $this->filesystem       = $filesystem;
        $this->version          = session('version');
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

            $this->processor->pushIntoQueue($file, $filename, $this->version);
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
     * Create Valid results.
     * @param $activityId
     * @param $results
     */
    public function create($activityId, $results)
    {
        $contents = json_decode(file_get_contents($this->getFilePath(true)), true);

        foreach ($results as $key => $result) {
            $resultData      = $contents[$result];
            $importedResults = ['result' => $resultData['data'], 'activity_id' => $activityId];
            $this->resultRepo->create($importedResults);
        }

        $this->resultImportStatus($results);
    }

    /**
     * Check the status of the csv results being imported.
     * @param $results
     */
    protected function resultImportStatus($results)
    {
        if (session('importing') && $this->checkStatusFile()) {
            $this->removeImportedResult($results);
        }

        if ($this->checkStatusFile() && is_null(session('importing'))) {
            $this->removeImportDirectory();
        }
    }

    /**
     * Remove the imported result if the csv is still being processed.
     * @param $checkedResults
     */
    protected function removeImportedResult($checkedResults)
    {
        $validResults = json_decode(file_get_contents($this->getFilePath(true)), true);
        foreach ($checkedResults as $key => $result) {
            unset($validResults[$key]);
        }

        FileFacade::put($this->getFilePath(true), $validResults);
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
        $dir = storage_path(sprintf('%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId));
        if (file_exists($dir)) {
            $this->filesystem->deleteDirectory($dir);
        }
    }

    /**
     * Set the key to specify that import process has started for the current User.
     * @param $filename
     * @return $this
     */
    public function startImport($filename)
    {
        $this->sessionManager->put(['import-result-status' => 'Processing']);
        $this->sessionManager->put(['filename' => $filename]);

        return $this;
    }

    /**
     * Remove the import-result-status key from the User's current session.
     */
    public function endImport()
    {
        $this->sessionManager->forget('import-result-status');
        $this->sessionManager->forget('filename');
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
     * Clear all invalid results.
     * @return bool|null
     */
    public function clearInvalidResults()
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
                sprintf('Error clearing invalid Results due to [%s]', $exception->getMessage()),
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
     * Set import-result-status key when the processing is complete.
     */
    public function setProcessCompleteToSession()
    {
        $this->sessionManager->put(['import-result-status' => 'Complete']);
    }

    /**
     * Get the Csv Import status from the current User's session.
     * @return mixed
     */
    public function getSessionStatus()
    {
        if($this->checkStatusFile()){
            $status = file_get_contents($this->getTemporaryFilepath('status.json'));

            if (json_decode($status, true)['status'] == 'Error') {
                return 'Error';
            }
        }

        if ($this->sessionManager->has('import-result-status')) {
            return $this->sessionManager->get('import-result-status');
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
        $results = json_decode(file_get_contents($filePath), true);
        $path    = $this->getTemporaryFilepath($temporaryFileName);

        $this->fixPermission($this->getTemporaryFilepath());

        FileFacade::put($path, json_encode($results));

        return view(sprintf('Activity.csvImporter.result.%s', $view), compact('results'))->render();
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
            $file->move($this->getStoredCsvPath(), str_replace(' ', '', $file->getClientOriginalName()));

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Result CSV file due to [%s]', $exception->getMessage()),
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
        if ($this->sessionManager->get('import-result-status') == 'Complete') {
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
    protected function fixPermission($path)
    {
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
        Event::fire(new ResultCsvWasUploaded($filename));
    }

    /**
     * Check if the import process is complete.
     * @return bool|string
     */
    public function importIsComplete()
    {
        $filePath = $this->getTemporaryFilepath('status.json');

        if (file_exists($filePath)) {
            $jsonContents = file_get_contents($filePath);
            $contents     = json_decode($jsonContents, true);

            if ($contents['status'] == 'Complete') {
                $this->setProcessCompleteToSession();
            }

            return $jsonContents;
        }

        return false;
    }

    /**
     * Fetch the processed data.
     * @param $filepath
     * @param $temporaryFilename
     * @return array|mixed
     */
    public function fetchData($filepath, $temporaryFilename)
    {
        $results  = json_decode(file_get_contents($filepath), true);
        $tempPath = $this->getTemporaryFilepath($temporaryFilename);

        if (file_exists($tempPath)) {
            $old   = json_decode(file_get_contents($tempPath), true);
            $diff  = array_diff_key($results, $old);
            $total = array_merge($diff, $old);

            FileFacade::put($tempPath, json_encode($total));
            $results = $diff;
        } else {
            FileFacade::put($tempPath, json_encode($results));
        }

        return $results;
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

    /**
     * Empty the previously imported result if status is present in session.
     */
    public function clearImport()
    {
        if ($this->sessionManager->has('import-result-status')) {
            $this->removeImportDirectory();
            $this->endImport();
        }
    }
}


