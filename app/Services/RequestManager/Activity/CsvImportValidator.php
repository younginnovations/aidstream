<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class CsvImportValidator
 * @package App\Services\RequestManager\Activity
 */
class CsvImportValidator
{
    public $validator;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->validator = $version->getActivityElement()->getCsvImportValidator();
    }
}
