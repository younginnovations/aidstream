<?php namespace App\Services\CsvImporter\Queue\Jobs;

use App\Jobs\Job;
use App\Services\CsvImporter\Queue\CsvProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
/**
 * Class ImportActivity
 * @package App\Services\CsvImporter\Queue\Jobs
 */
class ImportActivity extends Job implements ShouldQueue
{
    /**
     * @var CsvProcessor
     */
    protected $csvProcessor;

    /**
     * Current Organization's Id.
     * @var
     */
    protected $organizationId;

    /**
     * Current User's Id.
     * @var
     */
    protected $userId;

    /**
     * Directory where the uploaded Csv file is stored temporarily before import.
     */
    const UPLOADED_CSV_STORAGE_PATH = 'csvImporter/tmp/file';

    /**
     * @var
     */
    protected $filename;
    /**
     * @var
     */
    private $activityIdentifiers;

    private $version;
    /**
     * Create a new job instance.
     *
     * @param CsvProcessor $csvProcessor
     * @param              $filename
     * @param              $activityIdentifiers
     */
    public function __construct(CsvProcessor $csvProcessor, $filename, $activityIdentifiers)
    {
        $this->csvProcessor         = $csvProcessor;
        $this->organizationId       = session('org_id');
        $this->userId               = $this->getUserId();
        $this->filename             = $filename;
        $this->activityIdentifiers  = $activityIdentifiers;
        $this->version              = session('version');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $path = storage_path(sprintf('%s/%s/%s/%s', 'csvImporter/tmp/', $this->organizationId, $this->userId, 'status.json'));

            $this->csvProcessor->handle($this->organizationId, $this->userId, $this->activityIdentifiers, $this->version);
            $directoryPath = storage_path(sprintf('%s/%s/%s', 'csvImporter/tmp/', $this->organizationId, $this->userId));
            shell_exec(sprintf('chmod 777 -R %s', $directoryPath));

            file_put_contents($path, json_encode(['status' => 'Complete']));

            $uploadedFilepath = $this->getStoredCsvFilePath($this->filename);

            if (file_exists($uploadedFilepath)) {
                unlink($uploadedFilepath);
            }

            $this->delete();
        } catch (\Exception $exception) {
            file_put_contents($path, json_encode(['status' => 'Complete']));

            $this->delete();
        }

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
     * Get the temporary Csv filepath for the uploaded Csv file.
     * @param $filename
     * @return string
     */
    protected function getStoredCsvFilePath($filename)
    {
        return sprintf('%s/%s', storage_path(sprintf('%s/%s/%s', self::UPLOADED_CSV_STORAGE_PATH, $this->organizationId, $this->userId)), $filename);
    }
}
