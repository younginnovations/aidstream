<?php namespace App\Core\V202\Repositories\Activity;

use App\Core\Elements\CsvReader;
use App\Core\V201\Repositories\Activity\UploadTransaction as UploadTransactionV201;

/**
 * Class UploadTransaction
 * @package App\Core\V202\Repositories\Activity
 */
class UploadTransaction extends UploadTransactionV201
{
    /**
     * @var CsvReader
     */
    protected $readCsv;

    /**
     * @param CsvReader $readCsv
     */
    function __construct(CsvReader $readCsv)
    {
        $this->readCsv = $readCsv;
    }

    /**
     * format rows form simple csv
     * @param $transactionRow
     * @return array
     */
    public function formatFromSimpleCsv($transactionRow)
    {
        $transaction              = $this->readCsv->getTransactionHeaders('Detailed');
        $transaction['reference'] = $transactionRow['internal_reference'];
        if ($transactionRow['incoming_fund']) {
            $transaction['transaction_type'][0]['transaction_type_code'] = 1;
            $transaction['value'][0]['amount']                           = $transactionRow['incoming_fund'];

        } elseif ($transactionRow['expenditure']) {
            $transaction['transaction_type'][0]['transaction_type_code'] = 4;
            $transaction['value'][0]['amount']                           = $transactionRow['expenditure'];


        } elseif ($transactionRow['disbursement']) {
            $transaction['transaction_type'][0]['transaction_type_code'] = 3;
            $transaction['value'][0]['amount']                           = $transactionRow['disbursement'];

        } elseif ($transactionRow['commitment']) {
            $transaction['transaction_type'][0]['transaction_type_code'] = 2;
            $transaction['value'][0]['amount']                           = $transactionRow['commitment'];

        } else {
            $transaction['transaction_type'][0]['transaction_type_code'] = 2;
            $transaction['value'][0]['amount']                           = $transactionRow['incoming_commitment'];
        }
        $transaction['value'][0]['date']                                         = $transactionRow['transaction_date'];
        $transaction['transaction_date'][0]['date']                              = $transactionRow['transaction_date'];
        $transaction['description'][0]['narrative'][0]['narrative']              = $transactionRow['description'];
        $transaction['value'][0]['currency']                                     = '';
        $transaction['provider_organization'][0]['organization_identifier_code'] = $transactionRow['provider_org_reference'];
        $transaction['provider_organization'][0]['provider_activity_id']         = $transactionRow['provider_activity_id'];
        $transaction['provider_organization'][0]['narrative'][0]['narrative']    = $transactionRow['provider_org_name'];
        $transaction['receiver_organization'][0]['organization_identifier_code'] = $transactionRow['receiver_org_reference'];
        $transaction['receiver_organization'][0]['receiver_activity_id']         = $transactionRow['receiver_activity_id'];
        $transaction['receiver_organization'][0]['narrative'][0]['narrative']    = $transactionRow['receiver_org_name'];

        return $transaction;
    }
}
