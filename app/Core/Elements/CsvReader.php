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
        $rawFilePath = app_path("Core/%s/Template/Transaction/$fileName.json");
        $filePath    = sprintf($rawFilePath, session()->get('version'));
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, config('app.default_version_name'));

        return json_decode(file_get_contents($filePath), true);
    }

    /**
     * get the activity fields header form csv file
     * @param $fileName
     * @return array
     */
    public function getActivityHeaders($fileName)
    {
        $rawFilePath = app_path("Core/%s/Template/Activity/$fileName.json");
        $filePath    = sprintf($rawFilePath, session()->get('version'));
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, config('app.default_version_name'));

        return json_decode(file_get_contents($filePath), true);
    }
}
