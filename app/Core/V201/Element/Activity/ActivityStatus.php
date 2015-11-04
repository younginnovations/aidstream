<?php namespace App\Core\V201\Element\Activity;

/**
 * Class ActivityStatus
 * @package app\Core\V201\Element\Activity
 */
class ActivityStatus
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\ActivityStatus";
    }

    /**
     * @return activity status repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityStatus');
    }
}
