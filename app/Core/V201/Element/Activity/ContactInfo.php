<?php namespace App\Core\V201\Element\Activity;

/**
 * Class ContactInfo
 * @package app\Core\V201\Element\Activity
 */
class ContactInfo
{
    /**
     * @return contact info form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleContactInfo";
    }

    /**
     * @return contact Info repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ContactInfo');
    }
}
