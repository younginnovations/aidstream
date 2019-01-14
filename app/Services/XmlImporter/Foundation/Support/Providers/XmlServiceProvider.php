<?php namespace App\Services\XmlImporter\Foundation\Support\Providers;

use Sabre\Xml\Service;

/**
 * Class Xml
 * @package App\Services\XmlImporter
 */
class XmlServiceProvider
{
    /**
     * @var Service
     */
    protected $xmlService;

    /**
     * @var XmlErrorServiceProvider
     */
    protected $xmlErrorServiceProvider;

    /**
     * Xml versions that can be imported.
     *
     * @var array
     */
    protected $allowedXmlVersion = ['V103', 'V105', 'V201', 'V202', 'V203'];

    /**
     * Xml constructor.
     * @param Service                 $xmlService
     * @param XmlErrorServiceProvider $xmlErrorServiceProvider
     */
    public function __construct(Service $xmlService, XmlErrorServiceProvider $xmlErrorServiceProvider)
    {
        $this->xmlService              = $xmlService;
        $this->xmlErrorServiceProvider = $xmlErrorServiceProvider;
    }

    /**
     * Load xml data into an array|object|string.
     *
     * @param $data
     * @return array|object|string
     */
    public function load($data)
    {
        return $this->xmlService->parse($data);
    }

    /**
     * Get the version of the Xml.
     * If $versionName is set to true, returns the AidStream relevant name for the version, i.e., 'V103', 'V202', etc.
     * If $versionName is set to false, returns the version number, i.e., '1.03', '2.01, etc.
     *
     * @param      $data
     * @param bool $versionName
     * @return string
     */
    public function version($data, $versionName = false)
    {
        $document = new \SimpleXMLElement($data);

        if (!$versionName) {
            return strval($document['version']);
        }

        return 'V' . str_replace('.', '', $document['version']);
    }

    /**
     * Validate the uploaded Xml file against its schema.
     *
     * @param $contents
     * @return bool
     */
    public function isValidAgainstSchema($contents)
    {
        return $this->xmlErrorServiceProvider->load($contents)
                                             ->schemaValidate($this->getSchemaPath($this->version($contents, true)));
    }

    /**
     * Get the path for the Xml Schema according to the version of the Xml.
     *
     * @param $version
     * @return string
     */
    protected function getSchemaPath($version)
    {
        return app_path(sprintf('Core/%s/XmlSchema/iati-activities-schema.xsd', $version));
    }

    /**
     * Checks if the uploaded xml is allowed.
     *
     * @param $contents
     * @return bool
     */
    public function allowedVersionOfXml($contents)
    {
        $version = $this->version($contents, true);

        return (in_array($version, $this->allowedXmlVersion)) ? true : false;
    }
}

