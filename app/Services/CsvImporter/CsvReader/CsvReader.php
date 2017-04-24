<?php namespace App\Services\CsvImporter\CsvReader;


use Maatwebsite\Excel\Excel;

/**
 * Class CsvReader
 * @package App\Services\CsvImporter\CsvReader
 */
class CsvReader
{
    /**
     * @var Excel
     */
    protected $reader;

    /**
     * CsvReader constructor.
     * @param Excel $reader
     */
    public function __construct(Excel $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param        $file
     * @param bool   $checkEncoding
     * @param string $encoding
     * @return \Maatwebsite\Excel\Readers\LaravelExcelReader
     */
    public function load($file, $checkEncoding = true, $encoding = 'UTF-8')
    {
        if ($checkEncoding) {
            $encoding = getEncodingType($file);
        }

        return $this->reader->load($file, null, $encoding);
    }
}

