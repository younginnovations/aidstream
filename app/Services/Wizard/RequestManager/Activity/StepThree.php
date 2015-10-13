<?php namespace App\Services\Wizard\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class StepThree
 * @package App\Services\Wizard\RequestManager\Activity
 */
class StepThree
{
    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getStepThreeRequest();
    }
}
