<?php namespace app\Core\V201\Element\Activity;

/**
 * Class Description
 * return description form and description repository
 * @package app\Core\V201\Element\Activity
 */
class Description
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleDescription";
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Description');
    }
}
