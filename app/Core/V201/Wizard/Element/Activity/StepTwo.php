<?php namespace App\Core\V201\Wizard\Element\Activity;

/**
 * Class StepTwo
 * @package App\Core\V201\Wizard\Element\Activity
 */
class StepTwo
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Wizard\Forms\Activity\StepTwo";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Wizard\Repositories\Activity\StepTwo');
    }
}
