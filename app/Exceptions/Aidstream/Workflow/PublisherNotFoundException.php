<?php namespace App\Exceptions\Aidstream\Workflow;

use Exception;

/**
 * Class PublisherNotFoundException
 * @package App\Exceptions\Aidstream\Workflow
 */
class PublisherNotFoundException extends Exception
{
    /**
     * PublisherNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
