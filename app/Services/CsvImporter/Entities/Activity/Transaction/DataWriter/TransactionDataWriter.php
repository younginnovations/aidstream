<?php namespace App\Services\CsvImporter\Entities\Activity\Transaction\DataWriter;

use App\Models\Activity\Activity;
use Exception;


/**
 * Class TransactionDataWriter
 * @package App\Services\CsvImporter\Entities\Activity\Transaction\DataWriter
 */
class TransactionDataWriter
{
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var
     */
    protected $transactionRows;
    /**
     * @var
     */
    protected $organizationId;
    /**
     * @var
     */
    protected $activityId;
    /**
     * @var
     */
    protected $userId;
    /**
     * @var array
     */
    protected $references = [];

    /**
     * @var array
     */
    protected $dbReferences = [];
    /**
     * @var array
     */
    protected $validData = [];
    /**
     * @var array
     */
    protected $invalidData = [];

    /**
     * Path to store processed data.
     */
    const TRANSACTION_JSON_DATA_PATH = 'csvImporter/tmp/transaction';

    /**
     * Filename for valid data.
     */
    const VALID_JSON_FILENAME = 'valid.json';
    /**
     * Filename for invalid data.
     */
    const INVALID_JSON_FILENAME = 'invalid.json';

    /**
     * Filename for status of importer.
     */
    const STATUS_JSON_FILENAME = 'status.json';

    /**
     * TransactionDataWriter constructor.
     * @param $organizationId
     * @param $activityId
     * @param $userId
     */
    public function __construct($organizationId, $activityId, $userId)
    {
        $this->organizationId = $organizationId;
        $this->activityId     = $activityId;
        $this->userId         = $userId;
    }

    /**
     *Extract information for the processed data.
     *
     * @param $row
     * @return $this
     */
    public function extractDetails($row)
    {
        $this->transactionRows = $row;
        $this->storeStatus('Processing');
        $this->internalReferences();

        $reference                    = getVal((array) $this->transactionRows->data(), ['transaction', 'reference']);
        $this->data['transaction'][0] = getVal((array) $this->transactionRows->data(), ['transaction']);
        $status                       = $this->isInternalReferenceSame($reference);
        $alreadyExisted               = $this->isAlreadyPresentInDB($reference);
        ($reference == "") ?: $this->references[] = $reference;

        if (!$status) {
            $this->data['isValid'] = false;
            $this->data['errors']  = [trans('validation.unique', ['attribute' => trans('elementForm.transaction_internal_reference')])];
        } else {
            $this->data['isValid'] = $this->transactionRows->isValid;
            $this->data['errors']  = $this->getErrorMessages($this->transactionRows);
            $this->data['existed'] = $alreadyExisted;
            (!$alreadyExisted) ?: $this->data['transactionId'] = getVal($this->dbReferences[$reference], ['id']);
        }

        $this->storeValidity();

        return $this;
    }

    /**
     * Store the validity of the data.
     *
     */
    protected function storeValidity()
    {
        if ($this->data['isValid'] == true) {
            $this->validData[] = $this->data;
        } else {
            $this->invalidData[] = $this->data;
        }
    }

    /**
     * Store valid data to a file.
     *
     * @return $this
     */
    public function storeValidJson()
    {
        $this->storeJson(self::VALID_JSON_FILENAME, $this->validData);

        return $this;
    }

    /**
     * Store invalid data to a file.
     *
     * @return $this
     */
    public function storeInvalidJson()
    {
        $this->storeJson(self::INVALID_JSON_FILENAME, $this->invalidData);

        return $this;
    }

    /**
     * Store data to a json file.
     *
     * @param $filename
     * @param $data
     */
    public function storeJson($filename, $data)
    {
        $filePath = $this->getTransactionDataStoragePath();

        $this->createDirectoryIfNotExistent($filePath);

        file_put_contents(sprintf('%s/%s', $filePath, $filename), json_encode($data));
    }

    /**
     * Store status of the importer to json file.
     *
     * @param $status
     * @return $this
     */
    public function storeStatus($status)
    {
        $this->storeJson(self::STATUS_JSON_FILENAME, $status);

        return $this;
    }

    /**
     * Checks if the internal reference is same in the CSV.
     *
     * @param $reference
     * @return bool
     */
    protected function isInternalReferenceSame($reference)
    {
        $this->references = array_unique($this->references);
        if (in_array($reference, $this->references)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the internal reference is already present in DB.
     *
     * @param $reference
     * @return bool
     */
    protected function isAlreadyPresentInDB($reference)
    {
        if (array_key_exists($reference, $this->dbReferences)) {
            return true;
        }

        return false;
    }

    /**
     * @param $transaction
     * @return array
     */
    protected function getErrorMessages($transaction)
    {
        $errors = [];

        foreach (((array) $transaction->errors()) as $index => $message) {
            $errors[] = getVal($message, [0]);
        }

        return $errors;
    }

    /**
     * Returns path to storage of transaction file.
     *
     * @param null $filename
     * @return string
     */
    protected function getTransactionDataStoragePath($filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s/%s/%s/%s/%s', storage_path(), self::TRANSACTION_JSON_DATA_PATH, $this->organizationId, $this->userId, $this->activityId, $filename);
        }

        return sprintf('%s/%s/%s/%s/%s', storage_path(), self::TRANSACTION_JSON_DATA_PATH, $this->organizationId, $this->userId, $this->activityId);
    }

    /**
     * Create directory if not present.
     *
     * @param $path
     * @return $this
     */
    protected function createDirectoryIfNotExistent($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        shell_exec(sprintf('chmod 777 -R %s', $path));

        return $this;
    }

    /**
     * Returns and stores internal references from the Activity.
     *
     */
    public function internalReferences()
    {
        $activity     = Activity::findOrFail($this->activityId);
        $transactions = $activity->transactions->toArray();

        foreach ($transactions as $index => $transaction) {
            $reference = getVal($transaction, ['transaction', 'reference']);
            ($reference == "") ?: $this->dbReferences[$reference]['reference'] = $reference;
            ($reference == "") ?: $this->dbReferences[$reference]['id'] = getVal($transaction, ['id']);
        }
    }
}

