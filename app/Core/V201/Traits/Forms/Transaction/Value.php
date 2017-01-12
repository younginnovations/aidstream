<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class Value
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait Value
{
    /**
     * add value  form
     */
    public function addValue()
    {
        return $this->addCollection('value', 'Activity\Transactions\Value', '', [], trans('elementForm.value'));
    }

    /**
     * get Currency CodeList
     * @return mixed
     */
    public function getCurrencyCodeList()
    {
        return $this->getCodeList('Currency', 'Organization');
    }
}
