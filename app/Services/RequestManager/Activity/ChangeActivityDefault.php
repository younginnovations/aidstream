<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

class ChangeActivityDefault
{
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getChangeActivityDefaultRequest();
    }
}
