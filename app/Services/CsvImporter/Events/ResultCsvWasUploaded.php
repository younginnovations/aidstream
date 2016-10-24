<?php namespace App\Services\CsvImporter\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActivityCsvWasUploaded
 * @package App\Events
 */
class ResultCsvWasUploaded extends Event
{
    use SerializesModels;

    /**
     * @var
     */
    public $filename;

    /**
     * Create a new event instance.
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
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
