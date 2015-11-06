<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Transaction
 * @package App\Core\V201\Element\Activity
 */
class Transaction
{
    /**
     * @return transaction form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Transactions\Transactions';
    }

    /**
     * @return transaction repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Transaction');
    }
}
