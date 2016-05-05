<?php namespace App\Core\V201\Repositories\Activity;

use App\Core\Elements\CsvReader;
use App\Models\Activity\Activity;
use App\Models\Activity\Transaction as TransactionModel;

/**
 * Class UploadTransaction
 * @package App\Core\V201\Repositories\Activity
 */
class UploadTransaction
{
    const SIMPLE_CSV_COLUMN_COUNT = 15;
    /**
     * @var TransactionModel
     */
    protected $transaction;
    /**
     * @var Transaction
     */
    protected $transactionRepo;
    /**
     * @var CsvReader
     */
    protected $readCsv;

    /**
     * @param TransactionModel $transaction
     * @param Transaction      $transactionRepo
     * @param CsvReader        $readCsv
     */
    function __construct(TransactionModel $transaction, Transaction $transactionRepo, CsvReader $readCsv)
    {
        $this->transaction     = $transaction;
        $this->transactionRepo = $transactionRepo;
        $this->readCsv         = $readCsv;
    }

    /**
     * uploads new transaction
     * @param array    $transactionDetails
     * @param Activity $activity
     */
    public function upload(array $transactionDetails, Activity $activity)
    {
        $transaction = $this->transaction->newInstance(['transaction' => $transactionDetails]);
        $activity->transactions()->save($transaction);
    }

    /**
     * update transaction details
     * @param array $transactionDetails
     * @param       $transactionId
     */
    public function update(array $transactionDetails, $transactionId)
    {
        $transaction              = $this->transactionRepo->getTransaction($transactionId);
        $transaction->transaction = $transactionDetails;
        $transaction->save();
    }

    /**
     * prepare transaction array for upload
     * @param $transactionRow
     * @return array
     */
    public function formatFromExcelRow($transactionRow)
    {
        if (count($transactionRow) == self::SIMPLE_CSV_COLUMN_COUNT) {
            return $this->formatFromSimpleCsv($transactionRow);
        }

        return $this->formatFormDetailedCsv($transactionRow);
    }

    /**
     * format rows form detailed csv.
     * @param $transactionRow
     * @return array
     */
    protected function formatFormDetailedCsv($transactionRow)
    {
        $transaction                                                             = $this->readCsv->getTransactionHeaders('Detailed');
        $transaction['reference']                                                = $transactionRow['transaction_ref'];
        $transaction['transaction_type'][0]['transaction_type_code']             = $transactionRow['transactiontype_code'];
        $transaction['transaction_date'][0]['date']                              = date('Y-m-d', strtotime($transactionRow['transactiondate_iso_date']));
        $transaction['value'][0]['date']                                         = date('Y-m-d', strtotime($transactionRow['transactionvalue_value_date']));
        $transaction['value'][0]['amount']                                       = $transactionRow['transactionvalue_text'];
        $transaction['value'][0]['currency']                                     = '';
        $transaction['description'][0]['narrative'][0]['narrative']              = $transactionRow['description_text'];
        $transaction['provider_organization'][0]['organization_identifier_code'] = $transactionRow['providerorg_ref'];
        $transaction['provider_organization'][0]['provider_activity_id']         = $transactionRow['providerorg_provider_activity_id'];
        $transaction['provider_organization'][0]['narrative'][0]['narrative']    = $transactionRow['providerorg_narrative_text'];
        $transaction['receiver_organization'][0]['organization_identifier_code'] = $transactionRow['receiverorg_ref'];
        $transaction['receiver_organization'][0]['receiver_activity_id']         = $transactionRow['receiverorg_receiver_activity_id'];
        $transaction['receiver_organization'][0]['narrative'][0]['narrative']    = $transactionRow['receiverorg_narrative_text'];
        $transaction['disbursement_channel'][0]['disbursement_channel_code']     = $transactionRow['disbursementchannel_code'];
        $transaction['sector'][0]['sector_code']                                 = $transactionRow['sector_code'];
        $transaction['sector'][0]['sector_vocabulary']                           = $transactionRow['sector_vocabulary'];
        $transaction['recipient_country'][0]['country_code']                     = $transactionRow['recipientcountry_code'];
        $transaction['recipient_region'][0]['region_code']                       = $transactionRow['recipientregion_code'];
        $transaction['recipient_region'][0]['vocabulary']                        = $transactionRow['recipientregion_vocabulary'];
        $transaction['flow_type'][0]['flow_type']                                = $transactionRow['flowtype_code'];
        $transaction['finance_type'][0]['finance_type']                          = $transactionRow['financetype_code'];
        $transaction['aid_type'][0]['aid_type']                                  = $transactionRow['aidtype_code'];
        $transaction['tied_status'][0]['tied_status_code']                       = $transactionRow['tiedstatus_code'];

        return $transaction;
    }

    /**
     * get the references of all transaction
     * @return array
     */
    public function getTransactionReferences($activityId)
    {
        $transactions = $this->transaction->where('activity_id', $activityId)->get();
        $references   = [];

        foreach ($transactions as $transactionRow) {
            $references[$transactionRow->transaction['reference']] = $transactionRow->id;
        }

        return $references;
    }

    /**
     * format rows form simple csv
     * @param $transactionRow
     * @return array
     */
    protected function formatFromSimpleCsv($transactionRow)
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

        } else {
            $transaction['transaction_type'][0]['transaction_type_code'] = 2;
            $transaction['value'][0]['amount']                           = $transactionRow['commitment'];
        }
        $transaction['value'][0]['date']                                         = date('Y-m-d', strtotime($transactionRow['transaction_date']));
        $transaction['transaction_date'][0]['date']                              = date('Y-m-d', strtotime($transactionRow['transaction_date']));
        $transaction['description'][0]['narrative'][0]['narrative']              = $transactionRow['description'];
        $transaction['value'][0]['currency']                                     = '';
        $transaction['provider_organization'][0]['organization_identifier_code'] = $transactionRow['provider_org_reference'];
        $transaction['provider_organization'][0]['provider_activity_id']         = $transactionRow['provider_activity_id'];
        $transaction['provider_organization'][0]['narrative'][0]['narrative']    = $transactionRow['provider_org_name'];
        $transaction['receiver_organization'][0]['organization_identifier_code'] = $transactionRow['receiver_org_reference'];
        $transaction['receiver_organization'][0]['receiver_activity_id']         = $transactionRow['receiver_activity_id'];
        $transaction['receiver_organization'][0]['narrative'][0]['narrative']    = $transactionRow['receiver_org_name'];
        $transaction['receiver_organization'][0]['narrative'][0]['language']     = '';

        return $transaction;
    }

    /**
     * get the references of all transaction except transactionId
     * @return array
     */
    public function getTransactionReferencesExcept($activityId, $transactionId)
    {
        $transactions = $this->transaction->where(
            function ($query) use ($activityId, $transactionId) {
                $query->where('id', '<>', $transactionId);
                $query->where('activity_id', '=', $activityId);
            }
        )->get();
        $references   = [];

        foreach ($transactions as $transactionRow) {
            $references[$transactionRow->transaction['reference']] = $transactionRow->id;
        }

        return $references;
    }
}
