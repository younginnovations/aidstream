<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Condition
 * @package App\Core\V201\Element\Activity
 */
class Condition
{
    /**
     * @return condition form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Conditions';
    }

    /**
     * @return condition repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Condition');
    }
}
