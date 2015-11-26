<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class DisbursementChannel
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait DisbursementChannel
{
    /**
     *  add DisbursementChannel form
     * @return mixed
     */
    public function addDisbursementChannel()
    {
        return $this->addCollection('disbursement_channel', 'Activity\Transactions\DisbursementChannel');
    }

    /**
     * get DisbursementChannel CodeList
     * @return mixed
     */
    public function getDisbursementChannelCodeList()
    {
        return $this->getCodeList('DisbursementChannel', 'Activity');
    }
}
