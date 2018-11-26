<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
Use App;

/**
 * Class Tag
 * @package App\Services\RequestManager\Activity
 */
class Tag
{

    public $sector;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->tag = $version->getActivityElement()->getTagRequest();

        return $this->tag;
    }
}
