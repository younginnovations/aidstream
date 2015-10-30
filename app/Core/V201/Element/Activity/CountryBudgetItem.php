<?php namespace App\Core\V201\Element\Activity;

/**
 * Class CountryBudgetItem
 * @package app\Core\V201\Element\Activity
 */
class CountryBudgetItem
{
    /**
     * @return  country Budget Item form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\CountryBudgetItems";
    }

    /**
     * @return country Budget Item repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\CountryBudgetItem');
    }
}
