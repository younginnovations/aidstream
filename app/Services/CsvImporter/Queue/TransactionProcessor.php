<?php namespace App\Services\CsvImporter\Queue;


use App\Services\Activity\UploadTransactionManager;
use App\Services\CsvImporter\CsvReader\CsvReader;
use App\Services\CsvImporter\Queue\Jobs\ImportTransaction;
use App\Services\CsvImporter\TransactionCsvProcessor;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class TransactionProcessor
 * @package App\Services\CsvImporter\Queue
 */
class TransactionProcessor
{
    use DispatchesJobs;
    /**
     * @var Excel
     */
    protected $csvReader;
    /**
     * @var UploadTransactionManager
     */
    protected $uploadTransactionManager;


    /**
     * TransactionProcess constructor.
     * @param CsvReader $csvReader
     */
    public function __construct(CsvReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Dispatch the job to the queue.
     *
     * @param $file
     * @param $filename
     * @param $activityId
     * @param $userId
     */
    public function pushIntoQueue($file, $filename, $activityId, $userId)
    {
        $csv = $this->csvReader->load($file)->toArray();

        $this->dispatch(
            new ImportTransaction(
                new TransactionCsvProcessor($csv),
                $filename,
                session('org_id'),
                $activityId,
                $userId,
                session('version')
            )
        );
    }
}

