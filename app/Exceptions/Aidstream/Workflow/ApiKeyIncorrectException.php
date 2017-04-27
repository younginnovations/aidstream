<?php namespace App\Exceptions\Aidstream\Workflow;


use Exception;

/**
 * Class ApiKeyIncorrectException
 * @package App\Exceptions\Aidstream\Workflow
 */
class ApiKeyIncorrectException extends Exception
{
    /**
     * ApiKeyIncorrectException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}

