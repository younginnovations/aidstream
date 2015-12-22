<?php namespace App\Core\V202\Xml\Organization;

use App\Core\V201\Element\Organization\XmlService as V201XmlService;

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
     * @param XmlGenerator $xmlGenerator
     */
    function __construct(XmlGenerator $xmlGenerator)
    {
        $this->xmlGenerator = $xmlGenerator;
    }
}
