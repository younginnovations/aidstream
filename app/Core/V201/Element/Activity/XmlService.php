<?php namespace App\Core\V201\Element\Activity;

use Illuminate\Support\Facades\Session;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Activity
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
        $message = '';
        try {
            $xml = $this->xmlGenerator->getXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
            $xml->schemaValidate(app_path(sprintf('/Core/%s/XmlSchema/iati-activities-schema.xsd', Session::get('version'))));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = str_replace('DOMDocument::schemaValidate(): ', '', $message);
        }

        return $message;
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
}
