<?php namespace App\Core\V202\Xml\Activity;

use App\Core\V201\Element\Activity\XmlService as XmlService201;
use App\Services\Xml\XmlSchemaErrorParser;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Activity
 */
class XmlService extends XmlService201
{
    /**
     * @var XmlGenerator
     */
    protected $xmlGenerator;

    /**
     * @param XmlGenerator         $xmlGenerator
     * @param XmlSchemaErrorParser $xmlSchemaErrorParser
     */
    function __construct(XmlGenerator $xmlGenerator, XmlSchemaErrorParser $xmlSchemaErrorParser)
    {
        $this->xmlGenerator = $xmlGenerator;
        $this->xmlErrorParser = $xmlSchemaErrorParser;
    }
}
