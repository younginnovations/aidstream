<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App;

/**
 * Class ChangeActivityDefault
 * @package App\Core\V201\Element\Activity
 */
class ChangeActivityDefault extends BaseElement
{

    /**
     * get the activity default values form
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\ChangeActivityDefault";
    }

    /**
     * get activity default values repository
     */
    public function getRepository()
    {
        return app('App\Core\V201\Repositories\Activity\ChangeActivityDefault');
    }
}
