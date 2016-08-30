<?php namespace App\Services\CsvImporter\Listeners;

use App\Services\CsvImporter\Events\ActivityCsvWasUploaded;
use App\Services\CsvImporter\ImportManager;

/**
 * Class ActivityCsvUpload
 * @package App\Services\CsvImporter\Listeners
 */
class ActivityCsvUpload
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
     * @param  ActivityCsvWasUploaded $event
     * @return bool
     */
    public function handle(ActivityCsvWasUploaded $event)
    {
        $this->importManager->process($event->filename);

        return true;
    }
}
