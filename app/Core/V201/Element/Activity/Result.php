<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Result
 * @package app\Core\V201\Element\Activity
 */
class Result
{
    /**
     * @return result form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Results';
    }

    /**
     * @return result repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Result');
    }
}
