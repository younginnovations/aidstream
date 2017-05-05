<?php namespace App\Services\XmlImporter\Foundation\Queue;

use App\Jobs\Job;
use App\Services\XmlImporter\Foundation\XmlQueueProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportXml extends Job implements ShouldQueue
{
    /**
     * @var
     */
    protected $organizationId;
    /**
     * @var
     */
    protected $filename;
    /**
     * @var
     */
    protected $userId;

    /**
     * ImportXml constructor.
     * @param $organizationId
     * @param $userId
     * @param $filename
     */
    public function __construct($organizationId, $userId, $filename)
    {
        $this->organizationId = $organizationId;
        $this->filename       = $filename;
        $this->userId         = $userId;
    }

    public function handle()
    {
        try {
            $xmlImportQueue = app()->make(XmlQueueProcessor::class);
            $xmlImportQueue->import($this->filename, $this->organizationId, $this->userId);

            $this->delete();
        } catch (\Exception $exception) {
            $this->delete();
        }
    }
}