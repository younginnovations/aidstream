<?php namespace app\Core\V201\Element\Activity;

/**
 * Class RecipientRegion
 * @package app\Core\V201\Element\Activity
 */
class RecipientRegion
{
    /**
     * @return recipient region form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleRecipientRegion";
    }

    /**
     * @return recipient region repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\RecipientRegion');
    }
}
