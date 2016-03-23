<?php namespace App\Generator;

use App\Core\Version;
use App\Helpers\ArrayToXml;
use App\Models\Activity\Activity;

/**
 * Class XmlGenerator
 * @package App\Generator
 */
class XmlGenerator
{
    /**
     * @var ArrayToXml
     */
    protected $arrayToXml;

    /**
     * @var Version
     */
    protected $version;

    /**
     * @var
     */
    protected $activity;

    /**
     * XmlGenerator constructor.
     * @param ArrayToXml $arrayToXml
     * @param Version    $version
     */
    function __construct(ArrayToXml $arrayToXml, Version $version)
    {
        $this->arrayToXml = $arrayToXml;
        $this->version    = $version;
        $this->activity   = $this->version->getActivityElement();
    }

    /**
     * Gets the Xml file content.
     */
    public function getXml()
    {
        $this->generateXmlFile();
    }

    /**
     * Generate Xml file.
     */
    public function generateXmlFile()
    {
        $xmlData                  = array();
        $xmlData['@attributes']   = array(
            'version'            => $this->version->getVersion(),
            'generated-datetime' => gmdate('c')
        );
        $xmlData['iati-activity'] = $this->buildActivityXml();
        $xml                      = $this->arrayToXml->createXML('iati-activities', $xmlData);
        $filename                 = "activities";
        header('Content-Type: text/xml');
        header('Content-Disposition: attachment;filename=' . $filename . '.xml');
        echo $xml->saveXML();
    }

    /**
     * @return mixed
     */
    public function buildActivityXml()
    {
        $activities = Activity::all();
        $xmlData    = [];

        foreach ($activities as $activity) {
            $xmlData[] = $this->fetchActivityData($activity);
        }

        return $xmlData;
    }

    /**
     * Gets the required data from the activity.
     * @param $activity
     * @return array
     */
    public function fetchActivityData($activity)
    {
        $activityData                    = array();
        $activityData['@attributes']     = array(
            'xml:lang'              => 'en',
            'default-currency'      => 'USD',
            'last-updated-datetime' => $activity->created_at,
        );
        $activityData['iati-identifier'] = $activity->identifier;
        $activityData['title']           = $this->activity->getTitle()->getXmlData($activity);
        $activityData['description']     = $this->activity->getDescription()->getXmlData($activity);
        $activityData['transaction']     = $this->activity->getTransaction()->getXmlData($activity);

        return $activityData;
    }
}
