<?php namespace App\Services\CsvImporter\Listeners;

use App\Services\CsvImporter\Events\ResultCsvWasUploaded;
use App\Services\CsvImporter\ImportResultManager as ImportManager;

/**
 * Class ActivityCsvUpload
 * @package App\Services\CsvImporter\Listeners
 */
class ResultCsvUpload
{
    /**
     * @var ImportManager
     */
    protected $importManager;

    /**
     * Create the event listener.
     *
     * @param ImportManager $importManager
     */
    public function __construct(ImportManager $importManager)
    {
        $this->importManager = $importManager;
    }

    /**
     * Handle the event.
     *
     * @param  ResultCsvWasUploaded $event
     * @return bool
     */
    public function handle(ResultCsvWasUploaded $event)
    {
        $this->importManager->process($event->filename);

        return true;
    }
}
