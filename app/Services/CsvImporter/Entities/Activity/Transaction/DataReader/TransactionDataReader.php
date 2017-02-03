<?php namespace App\Services\CsvImporter\Entities\Activity\Transaction\DataReader;


/**
 * Class TransactionDataReader
 * @package App\Services\CsvImporter\Entities\Activity\Transaction\DataReader
 */
class TransactionDataReader
{
    /**
     * Path to read processed transaction data.
     */
    const TRANSACTION_JSON_DATA_PATH = 'csvImporter/tmp/transaction';

    /**
     * Filename to read valid data.
     */
    const VALID_JSON_FILENAME = 'valid.json';
    /**
     *Filename to read invalid data.
     */
    const INVALID_JSON_FILENAME = 'invalid.json';
    /**
     * Filename to read status of the importer.
     */
    const STATUS_JSON_FILENAME = 'status.json';

    /**
     * Returns valid data from the json.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return array|mixed
     */
    public function getValidJson($organizationId, $userId, $activityId)
    {
        return $this->getJson(self::VALID_JSON_FILENAME, $organizationId, $userId, $activityId);
    }

    /**
     * Returns invalid data from the json.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return array|mixed
     */
    public function getInValidJson($organizationId, $userId, $activityId)
    {
        return $this->getJson(self::INVALID_JSON_FILENAME, $organizationId, $userId, $activityId);
    }

    /**
     * Returns json file of the provided type.
     *
     * @param $filename
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return array|mixed
     */
    public function getJson($filename, $organizationId, $userId, $activityId)
    {
        $filePath = $this->jsonDataPath($organizationId, $userId, $activityId, $filename);

        if (file_exists($filePath)) {
            return json_decode(file_get_contents($filePath), true);
        }

        return [];
    }

    /**
     * Returns status of the importer.
     *
     * @param $organizationId
     * @param $userId
     * @param $activityId
     * @return array|mixed
     */
    public function getStatus($organizationId, $userId, $activityId)
    {
        return $this->getJson(self::STATUS_JSON_FILENAME, $organizationId, $userId, $activityId);
    }

    /**
     * Returns the path of the provided filename.
     *
     * @param      $organizationId
     * @param      $userId
     * @param      $activityId
     * @param null $filename
     * @return string
     */
    public function jsonDataPath($organizationId, $userId, $activityId, $filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s/%s/%s/%s', storage_path(self::TRANSACTION_JSON_DATA_PATH), $organizationId, $userId, $activityId, $filename);
        }

        return sprintf('%s/%s/%s/%s', storage_path(self::TRANSACTION_JSON_DATA_PATH), $organizationId, $userId, $activityId);
    }
}

