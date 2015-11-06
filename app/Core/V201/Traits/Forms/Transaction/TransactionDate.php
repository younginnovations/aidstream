<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class TransactionDate
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait TransactionDate
{
    /**
     * add transaction date form
     */
    public function addTransactionDate()
    {
        return $this->addCollection('transaction_date', 'Activity\Transactions\Date');
    }
}
