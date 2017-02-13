<?php namespace App\Services\CsvImporter\Traits;

use Maatwebsite\Excel\Excel;

/**
 * Class ChecksCsvHeaders
 * @package App\Services\CsvImporter\Traits
 */
trait ChecksCsvHeaders
{
    /**
     * Load Csv template
     * @param $version
     * @param $filename
     * @return array
     */
    protected function loadTemplate($version, $filename)
    {
        $excel = app()->make(Excel::class);

        $file = $excel->load(app_path(sprintf('Services/CsvImporter/Templates/Activity/%s/%s.csv', $version, $filename)));

        return $file->toArray();
    }

    /**
     * Check if the difference of the csv headers is empty.
     * @param array $diffHeaders
     * @return bool
     */
    protected function isSameCsvHeader(array $diffHeaders)
    {
        if (empty($diffHeaders)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the headers are correct for the uploaded Csv File.
     * @param        $csvHeaders
     * @param        $templateFileName
     * @param string $version
     * @return bool
     */
    protected function checkHeadersFor($csvHeaders, $templateFileName, $version = 'V201')
    {
        $templateHeaders = $this->loadTemplate($version, $templateFileName);
        $templateHeaders = array_keys($templateHeaders[0]);
        $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

        return $this->isSameCsvHeader($diffHeaders);
    }

    /**
     * Check if the headers for the uploaded Csv file matches with the provided header count.
     *
     * @param $actualHeaders
     * @param $providedHeaderCount
     * @return bool
     */
    protected function headerCountMatches( array $actualHeaders, $providedHeaderCount)
    {
        return (count($actualHeaders) == $providedHeaderCount);
    }

    /**
     * Check if the uploaded Csv file has correct headers.
     *
     * @return bool
     */
    protected function hasCorrectHeaders()
    {
        $csvHeaders = array_keys($this->csv[0]);

        if ($this->headerCountMatches($csvHeaders, self::BASIC_CSV_HEADERS_COUNT)) {
            return $this->checkHeadersFor($csvHeaders, 'basic_headers', 'V201');
        }

        if ($this->headerCountMatches($csvHeaders, self::TRANSACTION_CSV_HEADERS_COUNT)) {
            return $this->checkHeadersFor($csvHeaders, 'transaction_headers', 'V201');
        }

        if ($this->headerCountMatches($csvHeaders, self::ACTIVITY_OTHERS_HEADER_COUNT)) {
            return $this->checkHeadersFor($csvHeaders, 'other_fields_headers', 'V201');
        }

        if ($this->headerCountMatches($csvHeaders, self::ACTIVITY_TRANSACTION_OTHERS_HEADER_COUNT)) {
            return $this->checkHeadersFor($csvHeaders, 'other_fields_transaction_headers', 'V201');
        }

        return false;
    }
}

