<?php namespace App\Core\V201\Element\Organization;

use App\Core\V201\Traits\XmlServiceTrait;
use App\Services\Xml\XmlSchemaErrorParser;

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
    function __construct(XmlGenerator $xmlGenerator, XmlSchemaErrorParser $xmlSchemaErrorParser)
    {
        $this->xmlGenerator   = $xmlGenerator;
        $this->xmlErrorParser = $xmlSchemaErrorParser;
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

        $tempXml = $this->xmlGenerator->getXml($organization, $organizationData, $settings, $orgElem);
        $xml     = new \DOMDocument();
        $xmlFile = $tempXml->saveXML();
        $xml->loadXML($xmlFile);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-organisations-schema.xsd', session('version')));
        $errors     = [];
        if (!$xml->schemaValidate($schemaPath)) {
            $schemaErrors = $this->libxml_fetch_errors();
            $errors       = $this->getSpecificErrors($xmlFile, $schemaErrors);
        }

        return $errors;
    }

    /**
     * Get schema errors if it is present
     * @param $validateXml
     * @param $errors
     * @return array
     */
    public function getSpecificErrors($validateXml, $errors)
    {
        $errorsList = [];
        foreach ($errors as $error) {
            $errMessage = $this->xmlErrorParser->getModifiedError($error, $validateXml);
            isset($errorsList[$errMessage]) ? $errorsList[$errMessage] += 1 : $errorsList[$errMessage] = 1;
        }

        $messages = [];
        foreach ($errorsList as $message => $count) {
            if ($count > 1) {
                $newMessage = str_replace('The required', 'Multiple', $message);
                $newMessage = str_replace(' is ', ' are ', $newMessage);
            } else {
                $newMessage = $message;
            }
            $messages[] = $newMessage;
        }

        return $messages;
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

    public function generateTemporaryOrganizationXml($organization, $organizationData, $settings, $orgElem)
    {
        return $this->xmlGenerator->generateTemporaryXml($organization, $organizationData, $settings, $orgElem);
    }

    /**
     * get schema errors
     * @param $tempXmlContent
     * @param $version
     * @return array
     */
    public function getSchemaErrors($tempXmlContent, $version)
    {
        libxml_use_internal_errors(true);
        $xml = new \DOMDocument();
        $xml->loadXML($tempXmlContent);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-organisations-schema.xsd', $version));
        $messages   = [];
        if (!$xml->schemaValidate($schemaPath)) {
            $messages = $this->libxml_display_errors();
        }

        return $messages;
    }

    /**
     * get formatted xml
     * @param $tempXmlContent
     * @return array
     */
    public function getFormattedXml($tempXmlContent)
    {
        $xmlString = htmlspecialchars($tempXmlContent);
        $xmlString = str_replace(" ", "&nbsp;&nbsp;", $xmlString);
        $xmlLines  = explode("\n", $xmlString);

        return $xmlLines;
    }
}
