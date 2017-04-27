<?php namespace App\Exceptions\Aidstream;


use Exception;

/**
 * Class XmlFileNotFound
 * @package App\Exceptions\Aidstream
 */
class XmlFileNotFound extends Exception
{
    /**
     * XmlFileNotFound constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
