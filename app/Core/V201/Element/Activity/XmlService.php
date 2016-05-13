<?php namespace App\Core\V201\Element\Activity;

use App\Core\V201\Traits\XmlServiceTrait;
use App\Services\Xml\XmlSchemaErrorParser;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Activity
 */
class XmlService
{
    use XmlServiceTrait;

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
        $this->xmlGenerator   = $xmlGenerator;
        $this->xmlErrorParser = $xmlSchemaErrorParser;
    }


    /**
     * @param $activity
     * @param $transaction
     * @param $result
     * @param $settings
     * @param $activityElement
     * @param $orgElem
     * @param $organization
     */
    public function generateActivityXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization)
    {
        $this->xmlGenerator->generateXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);
    }

    public function generateTemporaryActivityXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization)
    {
        return $this->xmlGenerator->generateTemporaryXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);

    }

    /**
     * @param $filename
     * @param $organizationId
     * @param $publishedActivity
     * @return array
     */
    public function savePublishedFiles($filename, $organizationId, $publishedActivity)
    {
        return $this->xmlGenerator->savePublishedFiles($filename, $organizationId, $publishedActivity);
    }

    /**
     * @param $xmlFiles
     * @param $filename
     */
    public function getMergeXml($xmlFiles, $filename)
    {
        $this->xmlGenerator->getMergeXml($xmlFiles, $filename);
    }

    /**
     * @param $activity
     * @return string
     */
    public function segmentedXmlFile($activity)
    {
        return $this->xmlGenerator->segmentedXmlFile($activity);
    }

    /**
     * check if the specific xml is validate as per schema or not
     * @param $activityData
     * @param $transactionData
     * @param $resultData
     * @param $settings
     * @param $activityElement
     * @param $orgElem
     * @param $organization
     * @return array
     */
    public function validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization)
    {
        // Enable user error handling
        libxml_use_internal_errors(true);

        $tempXml = $this->xmlGenerator->getXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
        $xml     = new \DOMDocument();
        $xmlFile = $tempXml->saveXML();
        $xml->loadXML($xmlFile);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-activities-schema.xsd', session('version')));
        $errors     = [];
        if (!$xml->schemaValidate($schemaPath)) {
            $schemaErrors = $this->libxml_fetch_errors();
            $errors       = $this->getSpecificErrors($xmlFile, $schemaErrors);
        }

        return $errors;
    }

    /**
     * get all the modified errors
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
}
