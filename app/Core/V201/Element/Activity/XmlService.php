<?php namespace App\Core\V201\Element\Activity;

use App\Core\V201\Traits\XmlServiceTrait;

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
     * @param XmlGenerator $xmlGenerator
     */
    function __construct(XmlGenerator $xmlGenerator)
    {
        $this->xmlGenerator = $xmlGenerator;
    }

    /**
     * @param $activityData
     * @param $transactionData
     * @param $resultData
     * @param $settings
     * @param $activityElement
     * @param $orgElem
     * @param $organization
     * @return mixed
     */
    public function validateActivitySchema($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization)
    {
        // Enable user error handling
        libxml_use_internal_errors(true);

        $xml        = $this->xmlGenerator->getXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-activities-schema.xsd', session('version')));
        $messages   = '';
        if (!$xml->schemaValidate($schemaPath)) {
            $messages = $this->libxml_display_errors();
        }

        return $messages;
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

    /**
     * @param $filename
     * @param $organizationId
     * @param $publishedActivity
     */
    public function savePublishedFiles($filename, $organizationId, $publishedActivity)
    {
        $this->xmlGenerator->savePublishedFiles($filename, $organizationId, $publishedActivity);
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
     */
    public function segmentedXmlFile($activity)
    {
        $this->xmlGenerator->segmentedXmlFile($activity);
    }
}
