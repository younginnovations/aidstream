<?php namespace App\Core\V201\Element\Activity;

/**
 * Class Location
 * @package app\Core\V201\Element\Activity
 */
class Location
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Locations";
    }

    /**
     * @return location repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Location');
    }
}
