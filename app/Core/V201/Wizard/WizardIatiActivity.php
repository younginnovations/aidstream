<?php namespace App\Core\V201\Wizard;

use App;

/**
 * Class WizardIatiActivity
 * @package App\Core\V201\Wizard
 */
class WizardIatiActivity
{
    public function getWizardIatiIdentifier()
    {
        return app('App\Core\V201\Wizard\Element\Activity\IatiIdentifier');
    }

    public function getWizardRepository()
    {
        return app('App\Core\V201\Wizard\Repositories\Activity\ActivityRepository');
    }

    public function getWizardIatiIdentifierRequest()
    {
        return app('App\Core\V201\Wizard\Requests\Activity\IatiIdentifier');
    }

    public function getStepTwo()
    {
        return app('App\Core\V201\Wizard\Element\Activity\StepTwo');
    }

    public function getStepTwoRequest()
    {
        return app('App\Core\V201\Wizard\Requests\Activity\StepTwo');
    }

    public function getStepThree()
    {
        return app('App\Core\V201\Wizard\Element\Activity\StepThree');
    }

    public function getStepThreeRequest()
    {
        return app('App\Core\V201\Wizard\Requests\Activity\StepThree');
    }
}
