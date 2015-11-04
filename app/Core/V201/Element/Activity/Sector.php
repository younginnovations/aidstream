<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Sector
 * return description form and description repository
 * @package app\Core\V201\Element\Activity
 */
class Sector
{
    /**
     * @return sector form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Sectors";
    }

    /**
     * @return sector repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Sector');
    }
}
