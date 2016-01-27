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
        $transaction                                                             = $this->readCsv->getTransactionHeaders('Detailed');
        $transaction['reference']                                                = $transactionRow['transaction_ref'];
        $transaction['transaction_type'][0]['transaction_type_code']             = $transactionRow['transactiontype_code'];
        $transaction['transaction_date'][0]['date']                              = $transactionRow['transactiondate_iso_date'];
        $transaction['value'][0]['date']                                         = $transactionRow['transactionvalue_value_date'];
        $transaction['value'][0]['amount']                                       = $transactionRow['transactionvalue_text'];
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
}
