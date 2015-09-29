<?php namespace app\Core\V201\Element\Activity;

/**
 * Class Title
 * contains the function that returns the title form and title repository
 * @package app\Core\V201\Element\Activity
 */
class Title
{
    /**
     * @return title form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Title";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Title');
    }
}
