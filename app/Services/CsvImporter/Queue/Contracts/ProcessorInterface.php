<?php namespace App\Services\CsvImporter\Queue\Contracts;


use App\Services\CsvImporter\Queue\Exceptions\HeaderMismatchException;

interface ProcessorInterface
{
    /**
     * Push a CSV file's data for processing into Queue.
     * @param $file
     * @param $filename
     */
    public function pushIntoQueue($file, $filename);

    /**
     * Check if the headers are correct according to the provided template.
     * @param $csv
     * @return bool
     * @throws HeaderMismatchException
     */
    public function isCorrectCsv($csv);
}
