<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class TransactionType
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait TransactionType
{
    /**
     * add transaction type Form
     */
    public function addTransactionType()
    {
        return $this->addCollection('transaction_type', 'Activity\Transactions\Type');
    }

    /**
     * get TransactionType CodeList
     * @return mixed
     */
    public function getTransactionTypeCodeList()
    {
        return $this->getCodeList('TransactionType', 'Activity');
    }
}
