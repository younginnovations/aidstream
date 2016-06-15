<?php namespace App\Core\V202\Xml\Organization;

use App\Core\V201\Element\Organization\XmlService as V201XmlService;
use App\Services\Xml\XmlSchemaErrorParser;

/**
 * Class XmlService
 * @package App\Core\V202\Element\Organization
 */
class XmlService extends V201XmlService
{
    /**
     * @var XmlGenerator
     */
    protected $xmlGenerator;

    /**
     * @param XmlGenerator         $xmlGenerator
     * @param XmlSchemaErrorParser $xmlErrorParser
     */
    function __construct(XmlGenerator $xmlGenerator, XmlSchemaErrorParser $xmlErrorParser)
    {
        $this->xmlGenerator = $xmlGenerator;
        $this->xmlErrorParser = $xmlErrorParser;
    }
}
