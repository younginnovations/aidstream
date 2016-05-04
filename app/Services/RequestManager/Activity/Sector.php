<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Sector
 * @package App\Services\RequestManager\Activity
 */
class Sector
{

    public $sector;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->sector = $version->getActivityElement()->getSectorRequest();

        return $this->sector;
    }
}
