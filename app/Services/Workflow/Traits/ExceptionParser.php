<?php namespace App\Services\Workflow\Traits;


use App\Exceptions\Aidstream\Workflow\ApiKeyIncorrectException;
use App\Exceptions\Aidstream\Workflow\PublisherNotFoundException;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class ExceptionParser
 * @package App\Services\Workflow\Traits
 */
trait ExceptionParser
{
    /**
     * Returns message according to the exception thrown from api of IATI Registry.
     *
     * @param $exception
     * @return array
     */
    public function parse($exception)
    {
        if ($exception instanceof ConnectException) {
            return ['status' => false, 'message' => trans('error.connection_error')];
        }

        if ($exception instanceof PublisherNotFoundException) {
            return ['status' => false, 'message' => trans('error.publisher_not_found')];
        }

        if ($exception instanceof ApiKeyIncorrectException) {
            return ['status' => false, 'message' => trans('error.not_authorized')];
        }

        if ($exception instanceof ClientException) {
            if ($exception->getResponse()->getStatusCode() == self::PACKAGE_NOT_FOUND_ERROR_CODE) {
                return ['status' => false, 'message' => trans('error.package_not_found')];
            }
        }

        if ($exception instanceof FileNotFoundException) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }

        try {
            if (getVal(explode(':', explode("\n", $exception->getMessage())[0]), [0]) == self::NOT_AUTHORIZED_ERROR_CODE) {
                return ['status' => false, 'message' => trans('error.not_authorized')];
            }

            if (trim(getVal(explode(":", $exception->getMessage()), [3])) == 'I/O warning') {
                return ['status' => false, 'message' => trans('error.xml_file_not_found')];
            }

            if (trim($exception->getMessage()) === 'Trying to get property of non-object') {
                return ['status' => false, 'message' => trans('error.not_allowed')];
            }

        } catch (Exception $exception) {
            return ['status' => false, 'message' => $exception->getTraceAsString()];
        }


        return ['status' => false, 'message' => $exception->getMessage()];
    }
}

