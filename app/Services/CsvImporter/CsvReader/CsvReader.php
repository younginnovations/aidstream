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
     * @return \Maatwebsite\Excel\Readers\LaravelExcelReader
     */
    public function load($file)
    {
        $encoding = getEncodingType($file);

        if (!in_array($encoding, mb_list_encodings())) {
            $encoding = null;
        }

        return $this->reader->load($file, null, $encoding);
    }
}

