<?php namespace App\Core\V202\Xml\Activity;

use App\Core\V201\Element\Activity\XmlService as XmlService201;

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
     * @param XmlGenerator $xmlGenerator
     */
    function __construct(XmlGenerator $xmlGenerator)
    {
        $this->xmlGenerator = $xmlGenerator;
    }
}
