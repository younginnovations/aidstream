<?php namespace App\Core\V201\Element\Activity;

/**
 * Class XmlService
 * @package App\Core\V201\Element\Activity
 */
class XmlService extends XmlGenerator
{
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
            $xml = $this->getXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
            $xml->schemaValidate(app_path('/Core/V201/XmlSchema/iati-activities-schema.xsd'));
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
        $this->generateXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);
    }
}
