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

        $transactions = $this->groupBySector();

        if($this->version == 'V203') {
            $transactions = $this->groupByAidType($transactions);
        }

        foreach ($transactions as $index => $row) {
            $this->data = $this->initialize($row)
                               ->process()
                               ->validate();

            $dataWriter->extractDetails($this->data)
                       ->storeValidJson()
                       ->storeInvalidJson();
        }

        $dataWriter->storeStatus('Completed');
    }

    public function groupBySector()
    {
        $transactions = [];

        foreach ($this->rows() as $index => $row) {
            $shouldMerge     = true;
            $shouldDeleteRow = true;

            foreach ($row as $key => $value) {
                if ($value) {
                    $shouldDeleteRow = false;
                    if ($key != 'sector_vocabulary' && $key != 'sector_code') {
                        $shouldMerge = false;
                    }
                }

                if (!$shouldDeleteRow) {
                    $transactions[$index][$key] = $value;

                    if ($key === 'sector_vocabulary') {
                        $transactions[$index]['sector'][0]['sector_vocabulary'] = $value;
                        unset($transactions[$index]['sector_vocabulary']);
                    }

                    if ($key === 'sector_code') {
                        $transactions[$index]['sector'][0]['sector_code'] = $value;
                        unset($transactions[$index]['sector_code']);
                    }
                }

            }
            if ($shouldMerge && $index != 0) {
                array_push($transactions[$index - 1]['sector'], $transactions[$index]['sector'][0]);
                unset($transactions[$index]);
            }
        }

        return $transactions;
    }

    public function groupByAidType($transactions)
    {
        foreach ($transactions as $index => $row) {
            if(!array_key_exists('aid_type_code', $row)){
                return $transactions;
            }

            $rows = [];
            $aidTypeArray = [];
            if($row['aid_type_code'] !== null){
                $rows = explode(';', $row['aid_type_code']);
                $rows = collect($rows)->map(
                    function($rows) {
                        return [
                            "default_aid_type"              => $rows,
                            "default_aidtype_vocabulary"    => ($rows ? "1" : ""),
                            "aidtype_earmarking_category"   => "",
                            "default_aid_type_text"         => ""
                        ];
                    }
                )->toArray();

            $aidTypeArray[]['aid_type'] = $rows;
            $transactions[$index]['aid_type_code'] = $aidTypeArray;
            } else {
                $transactions[$index]['aid_type_code'] = [];
            }
        }

        return $transactions;
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