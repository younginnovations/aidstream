<?php namespace App\Core\V201\Wizard\Element\Activity;

/**
 * Class IatiIdentifier
 * @package app\Core\V201\Element\Activity
 */
class IatiIdentifier
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Wizard\Forms\Activity\IatiIdentifier";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Wizard\Repositories\Activity\IatiIdentifier');
    }
}
