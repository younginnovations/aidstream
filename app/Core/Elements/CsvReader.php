<?php namespace App\Core\Elements;

/**
 * Class CsvReader
 * @package App\Core\Elements
 */
class CsvReader
{
    /**
     * get the transaction fields header form csv file
     * @param $fileName
     * @return array
     */
    public function getTransactionHeaders($fileName)
    {
        $fileContents = file_get_contents(app_path("Core/" . session()->get('version') . "/Template/Transaction/$fileName.json"));

        return json_decode($fileContents, true);
    }
}
