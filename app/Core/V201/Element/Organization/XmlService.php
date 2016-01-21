<?php namespace App\Core\V201\Element\Organization;

use App\Core\V201\Traits\XmlServiceTrait;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Organization
 */
class XmlService
{
    use XmlServiceTrait;

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

    /**
     * validates organization data with xml schema
     * @param $organization
     * @param $organizationData
     * @param $settings
     * @param $orgElem
     * @return mixed
     */
    public function validateOrgSchema($organization, $organizationData, $settings, $orgElem)
    {
        // Enable user error handling
        libxml_use_internal_errors(true);

        $xml        = $this->xmlGenerator->getXml($organization, $organizationData, $settings, $orgElem);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-organisations-schema.xsd', session('version')));
        $message    = '';
        if (!$xml->schemaValidate($schemaPath)) {
            $message = $this->libxml_display_errors();
        }

        return $message;
    }

    /**
     * @param $organization
     * @param $organizationData
     * @param $settings
     * @param $orgElem
     * @return mixed
     */
    public function generateOrgXml($organization, $organizationData, $settings, $orgElem)
    {
        return $this->xmlGenerator->generateXml($organization, $organizationData, $settings, $orgElem);
    }

}
