<?php
namespace App\Generator;

use App\Core\Version;
use App\Helpers\ArrayToXml;
use App\Models\Activity;

class XmlGenerator {

    protected $arrayToXml;
    protected $version;
    protected $activity;
    function __construct(ArrayToXml $arrayToXml , Version $version)
    {
        $this->arrayToXml = $arrayToXml;
        $this->version = $version;
        $this->activity = $this->version->getActivityElement();
    }

    public function getXml()
    {
        $this->generateXmlFile();
    }

    public  function generateXmlFile()
    {
        $xmlData = array();
        $xmlData['@attributes'] = array(
            'version' => $this->version->getVersion(),
            'generated-datetime' => gmdate('c')
        );
        $xmlData['iati-activity'] = $this->buildActivityXml();
        $xml = $this->arrayToXml->createXML('iati-activities', $xmlData);
        $filename = "activities";
        header( 'Content-Type: text/xml' );
        header( 'Content-Disposition: attachment;filename='.$filename.'.xml');
        echo $xml->saveXML();
    }

    /**
     * @return mixed
     */
    public function buildActivityXml()
    {
        $activities = Activity::all();
        foreach ($activities as $activity) {
            $xmlData[] = $this->fetchActivityData($activity);
        }
        return $xmlData;
    }

    public function fetchActivityData($activity)
    {
        $activityData = array();
        $activityData['@attributes'] = array(
                'xml:lang' => 'en',
                'default-currency'=>'USD',
                'last-updated-datetime'=>$activity->created_at,
            );
        $activityData['iati-identifier'] =  $activity->identifier;
        $activityData['title']  = $this->activity->getTitle()->getXmlData($activity);
        $activityData['description'] = $this->activity->getDescription()->getXmlData($activity) ;
        $activityData['transaction']= $this->activity->getTransaction()->getXmlData($activity);
        return $activityData;
    }

}
