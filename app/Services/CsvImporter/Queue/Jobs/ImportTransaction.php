<?php namespace App\Services\CsvImporter\Queue\Jobs;


use App\Jobs\Job;
use App\Services\Activity\UploadTransactionManager;
use App\Services\CsvImporter\TransactionCsvProcessor;

/**
 * Class ImportTransaction
 * @package App\Services\CsvImporter\Queue\Jobs
 */
class ImportTransaction extends Job
{
    /**
     * @var UploadTransactionManager
     */
    protected $csvProcessor;
    /**
     * @var
     */
    protected $filename;
    /**
     * @var
     */
    protected $orgId;
    /**
     * @var
     */
    protected $activityId;
    /**
     * @var
     */
    protected $userId;
    /**
     * @var
     */
    protected $version;

    /**
     * ImportTransaction constructor.
     * @param TransactionCsvProcessor $csvProcessor
     * @param                         $filename
     * @param                         $orgId
     * @param                         $activityId
     * @param                         $userId
     * @param                         $version
     */
    public function __construct(
        TransactionCsvProcessor $csvProcessor,
        $filename,
        $orgId,
        $activityId,
        $userId,
        $version
    ) {
        $this->csvProcessor = $csvProcessor;
        $this->filename     = $filename;
        $this->orgId        = $orgId;
        $this->activityId   = $activityId;
        $this->userId       = $userId;
        $this->version      = $version;
    }

    /**
     *  Initiate transaction csv processing.
     *
     */
    public function handle()
    {
        $this->csvProcessor->handle($this->orgId, $this->activityId, $this->userId, $this->version);

        $this->delete();
    }
}

