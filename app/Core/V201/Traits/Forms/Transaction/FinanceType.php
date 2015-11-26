<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class FinanceType
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait FinanceType
{
    /**
     * add finance type form
     * @return mixed
     */
    public function addFinanceType()
    {
        return $this->addCollection('finance_type', 'Activity\Transactions\FinanceType');
    }

    /**
     * get FinanceType CodeList
     * @return mixed
     */
    public function getFinanceTypeCodeList()
    {
        return $this->getCodeList('FinanceType', 'Activity');
    }
}
