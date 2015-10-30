<?php namespace App\Core\V201\Element\Activity;

/**
 * Class OtherIdentifier
 * @package app\Core\V201\Element\Activity
 */
class OtherIdentifier
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleOtherIdentifier";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\OtherIdentifierRepository');
    }
}
