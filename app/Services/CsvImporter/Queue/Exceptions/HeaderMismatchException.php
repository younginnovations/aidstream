<?php namespace App\Services\CsvImporter\Queue\Exceptions;

use Exception;

/**
 * Class HeaderMismatchException
 * @package App\Services\CsvImporter\Queue\Exceptions
 */
class HeaderMismatchException extends Exception
{
    /**
     * Message for the HeaderMismatch Exception.
     */
    const MESSAGE = 'The headers in the uploaded Csv file do not match with the provided template.';

    /**
     * HeaderMismatchException constructor.
     */
    public function __construct()
    {
        $this->message = self::MESSAGE;
    }
}
