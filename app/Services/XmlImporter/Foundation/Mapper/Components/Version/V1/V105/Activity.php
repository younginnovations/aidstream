<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\V105;

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Activity as V1BaseActivity;

/**
 * Class Activity
 * @package App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\V105
 */
class Activity extends V1BaseActivity
{
    /**
     * Map imported activity to respective elements.
     *
     * @param $activity
     * @param $template
     * @return array
     */
    public function map($activity, $template)
    {
        foreach ($activity as $index => $element) {
            $elementName = $this->name($element);
            $this->resetIndex($elementName);
            $this->activity[$this->activityElements[$elementName]] = $this->$elementName($element, $template);
        }

        return $this->activity;
    }

    /**
     * Map location from the imported V105 XML.
     *
     * @param $element
     * @param $template
     * @return array
     */
    public function location($element, $template)
    {
        $this->location[$this->index]                                         = $template['location'];
        $this->location[$this->index]['reference']                            = $this->attributes($element, 'ref');
        $this->location[$this->index]['location_reach'][0]['code']            = $this->attributes($element, 'code', 'locationReach');
        $this->location[$this->index]['location_id'][0]['vocabulary']         = $this->attributes($element, 'vocabulary', 'locationId');
        $this->location[$this->index]['location_id'][0]['code']               = $this->attributes($element, 'code', 'locationId');
        $this->location[$this->index]['name'][0]['narrative']                 = $this->groupNarrative($element['value'], 'name');
        $this->location[$this->index]['location_description'][0]['narrative'] = $this->groupNarrative($element['value'], 'description');
        $this->location[$this->index]['activity_description'][0]['narrative'] = $this->groupNarrative($element['value'], 'activityDescription');
        $this->location[$this->index]['administrative']                       = $this->filterAttributes(getVal($element, ['value'], []), 'administrative', ['code', 'vocabulary', 'level']);
        $this->location[$this->index]['point'][0]['srs_name']                 = $this->attributes($element, 'srsName', 'point');
        $this->location[$this->index]['point'][0]['position'][0]              = $this->latAndLong(getVal($element, ['value'], []));
        $this->location[$this->index]['exactness'][0]['code']                 = $this->attributes($element, 'code', 'exactness');
        $this->location[$this->index]['location_class'][0]['code']            = $this->attributes($element, 'code', 'locationClass');
        $this->location[$this->index]['feature_designation'][0]['code']       = $this->attributes($element, 'code', 'featureDesignation');
        $this->index ++;

        return $this->location;
    }
}

