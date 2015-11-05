<?php namespace App\Core\V201\Element\Activity;

/**
 * Class DefaultFlowType
 * @package app\Core\V201\Element\Activity
 */
class DefaultFlowType
{
    /**
     * @return default flow type form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\DefaultFlowType';
    }

    /**
     * @return default flow type repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\DefaultFlowType');
    }
}
