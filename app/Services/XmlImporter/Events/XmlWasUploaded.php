<?php namespace App\Services\XmlImporter\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class XmlWasUploaded
 * @package App\Services\XmlImporter\Events
 */
class XmlWasUploaded extends Event
{
//    use SerializesModels;

    /**
     * @var
     */
    public $filename;

    /**
     * @var
     */
    public $userId;

    /**
     * @var
     */
    public $organizationId;

    /**
     * XmlWasUploaded constructor.
     * @param $filename
     * @param $userId
     * @param $organizationId
     */
    public function __construct($filename, $userId, $organizationId)
    {
        $this->filename       = $filename;
        $this->userId         = $userId;
        $this->organizationId = $organizationId;
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