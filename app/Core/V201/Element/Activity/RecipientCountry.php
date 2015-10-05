<?php namespace app\Core\V201\Element\Activity;

/**
 * Class RecipientCountry
 * @package app\Core\V201\Element\Activity
 */
class RecipientCountry
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleRecipientCountry";
    }

    /**
     * @return recipient country repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\RecipientCountry');
    }
}
