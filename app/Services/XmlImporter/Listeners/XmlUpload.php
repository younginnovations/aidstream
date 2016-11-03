<?php namespace App\Services\XmlImporter\Listeners;

use App\Services\XmlImporter\Events\XmlWasUploaded;
use App\Services\XmlImporter\Foundation\Queue\ImportXml;
use App\Services\XmlImporter\Foundation\XmlQueueProcessor;
use App\Services\XmlImporter\XmlImportManager;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class XmlUpload
 * @package App\Services\XmlImporter\Listeners
 */
class XmlUpload
{
    use DispatchesJobs;

    /**
     * @var XmlImportManager
     */
    protected $xmlImportManager;

    /**
     * @var XmlQueueProcessor
     */
    protected $xmlQueueProcessor;

    /**
     * XmlUpload constructor.
     * @param XmlImportManager  $xmlImportManager
     * @param XmlQueueProcessor $xmlQueueProcessor
     */
    public function __construct(XmlImportManager $xmlImportManager, XmlQueueProcessor $xmlQueueProcessor)
    {
        $this->xmlImportManager  = $xmlImportManager;
        $this->xmlQueueProcessor = $xmlQueueProcessor;
    }

    /**
     * Handle the XmlWasUploadedEvent.
     *
     * @param XmlWasUploaded $event
     * @return bool
     */
    public function handle(XmlWasUploaded $event)
    {
        $this->dispatch(new ImportXml($event->organizationId, $event->userId, $event->filename));

        return true;
    }
}