<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class TiedStatus
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait TiedStatus
{
    /**
     * add tied status form
     * @return mixed
     */
    public function addTiedStatus()
    {
        return $this->addCollection('tied_status', 'Activity\Transactions\TiedStatus');
    }

    /**
     * get TiedStatus CodeList
     * @return mixed
     */
    public function getTiedStatusCodeList()
    {
        return $this->getCodeList('TiedStatus', 'Activity');
    }
}
