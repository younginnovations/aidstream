<?php namespace App\Core\V201\Element\Activity;

/**
 * Class ActivityDate
 * @package app\Core\V201\Element\Activity
 */
class ActivityDate
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleActivityDate";
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityDate');
    }
}
