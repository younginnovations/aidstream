<?php namespace App\Core\V201\Wizard\Element\Activity;

/**
 * Class StepThree
 * @package App\Core\V201\Wizard\Element\Activity
 */
class StepThree
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Wizard\Forms\Activity\StepThree";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Wizard\Repositories\Activity\StepThree');
    }
}
