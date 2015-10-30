<?php namespace App\Services\Wizard\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class StepTwo
 * @package App\Services\Wizard\RequestManager\Activity
 */
class StepTwo
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getStepTwoRequest();
    }
}
