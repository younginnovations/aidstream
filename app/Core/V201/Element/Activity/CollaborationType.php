<?php namespace App\Core\V201\Element\Activity;

/**
 * Class CollaborationType
 * @package app\Core\V201\Element\Activity
 */
class CollaborationType
{
    /**
     * @return  country Budget Item form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\CollaborationType";
    }

    /**
     * @return country Budget Item repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\CollaborationType');
    }
}
