<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Budget
 * @package App\Core\V201\Element\Activity
 */
class Budget
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Budgets';
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Budget');
    }
}
