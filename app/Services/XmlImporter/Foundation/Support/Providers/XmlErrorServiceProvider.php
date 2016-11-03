<?php namespace App\Services\XmlImporter\Foundation\Support\Providers;

use DOMDocument;

/**
 * Class XmlErrorServiceProvider
 * @package App\Services\XmlImporter\Foundation\Support\Providers
 */
class XmlErrorServiceProvider
{
    /**
     * Load the contents of an Xml file to check if there are any schema errors.
     *
     * @param $contents
     * @return \DOMDocument
     */
    public function load($contents)
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->loadXML($contents);

        return $document;
    }
}
