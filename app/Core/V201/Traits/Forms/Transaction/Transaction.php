<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class Transaction
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait Transaction
{
    /**
     * add transaction  form
     */
    public function addTransaction()
    {
        return $this->addCollection('transaction', 'Activity\Transactions\Transaction');
    }
}