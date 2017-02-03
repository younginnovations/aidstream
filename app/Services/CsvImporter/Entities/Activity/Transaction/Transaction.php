<?php namespace App\Services\CsvImporter\Entities\Activity\Transaction;


use App\Services\CsvImporter\Entities\Activity\Transaction\DataWriter\TransactionDataWriter;
use App\Services\CsvImporter\Entities\TransactionCsv;
use Exception;

/**
 * Class Transaction
 * @package App\Services\CsvImporter\Entities\Activity\Transaction
 */
class Transaction extends TransactionCsv
{
    /**
     * @var array
     */
    protected $rows;
    /**
     * @var
     */
    protected $activityId;
    /**
     * @var
     */
    protected $version;
    /**
     * @var
     */
    protected $organizationId;
    /**
     * @var
     */
    protected $userId;
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Transaction constructor.
     * @param array $rows
     * @param       $organizationId
     * @param       $activityId
     * @param       $userId
     * @param       $version
     */
    public function __construct(array $rows, $organizationId, $activityId, $userId, $version)
    {
        $this->rows           = $rows;
        $this->organizationId = $organizationId;
        $this->activityId     = $activityId;
        $this->userId         = $userId;
        $this->version        = $version;
    }

    /**
     * Process the uploaded csv data.
     *
     */
    public function process()
    {
        $dataWriter = $this->dataWriterClass();
        $dataWriter->internalReferences();

        foreach ($this->rows() as $index => $row) {
            $this->data = $this->initialize($row)
                               ->process()
                               ->validate();

            $dataWriter->extractDetails($this->data)
                       ->storeValidJson()
                       ->storeInvalidJson();
        }

        $dataWriter->storeStatus('Completed');
    }

    /**
     * Returns transaction data writer class.
     * @return mixed
     */
    public function dataWriterClass()
    {
        return app()->make(TransactionDataWriter::class, [$this->organizationId, $this->activityId, $this->userId]);
    }
}