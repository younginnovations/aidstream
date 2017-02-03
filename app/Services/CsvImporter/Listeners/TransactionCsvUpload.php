<?php namespace App\Services\CsvImporter\Listeners;


use App\Services\Activity\UploadTransactionManager;
use App\Services\CsvImporter\Events\TransactionCsvWasUploaded;
use App\Services\CsvImporter\ImportTransactionManager;

class TransactionCsvUpload
{
    /**
     * @var UploadTransactionManager
     */
    protected $transactionManager;

    /**
     * TransactionCsvUpload constructor.
     * @param ImportTransactionManager $transactionManager
     */
    public function __construct(ImportTransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    public function handle(TransactionCsvWasUploaded $event)
    {
        $this->transactionManager->process($event->filename, $event->activityId);
    }
}

