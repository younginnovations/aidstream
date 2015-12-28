<?php namespace App\Core\V201\Element\Organization;

use Illuminate\Support\Facades\Session;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Organization
 */
class XmlService
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
        $message = '';
        try {
            $xml = $this->xmlGenerator->getXml($organization, $organizationData, $settings, $orgElem);
            $xml->schemaValidate(app_path(sprintf('/Core/%s/XmlSchema/iati-organisations-schema.xsd', Session::get('version'))));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = str_replace('DOMDocument::schemaValidate(): ', '', $message);
        }

        return $message;
    }

    /**
     * generates xml from organization data
     * @param $organization
     * @param $organizationData
     * @param $settings
     * @param $orgElem
     */
    public function generateOrgXml($organization, $organizationData, $settings, $orgElem)
    {
        $this->xmlGenerator->generateXml($organization, $organizationData, $settings, $orgElem);
    }

}
