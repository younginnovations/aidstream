<?php namespace App\Services\CsvImporter\Events;


use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class TransactionCsvWasUploaded
 * @package App\Services\CsvImporter\Events
 */
class TransactionCsvWasUploaded extends Event
{
    use SerializesModels;

    /**
     * @var
     */
    public $filename;

    /**
     * @var
     */
    public $activityId;

    /**
     * Create a new event instance.
     *
     * @param $filename
     * @param $activityId
     */
    public function __construct($filename, $activityId)
    {
        $this->filename   = $filename;
        $this->activityId = $activityId;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

