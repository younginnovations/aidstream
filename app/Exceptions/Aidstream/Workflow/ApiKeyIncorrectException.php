<?php namespace App\Exceptions\Aidstream\Workflow;


use Exception;

class ApiKeyIncorrectException extends Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}

