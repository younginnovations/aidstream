<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class Description
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait Description
{
    /**
     * add description form
     * @return mixed
     */
    public function addDescription()
    {
        return $this->addCollection('description', 'Activity\Transactions\Description');
    }
}
