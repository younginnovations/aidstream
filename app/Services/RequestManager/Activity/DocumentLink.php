<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class DocumentLink
 * @package App\Services\RequestManager\Activity
 */
class DocumentLink
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getDocumentLinkRequest();
    }
}
