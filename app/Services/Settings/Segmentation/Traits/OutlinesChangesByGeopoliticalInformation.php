<?php namespace App\Services\Settings\Segmentation\Rule\Traits;


/**
 * Trait OutlinesChangesByGeopoliticalInformation
 * @package App\Services\Settings\Segmentation\Rule\Traits
 */
trait OutlinesChangesByGeopoliticalInformation
{
    /**
     * Get the changes for segmented publishing type by Recipient Country.
     * @param $countryGroup
     * @param $publisherId
     * @return $this
     */
    protected function getChangesForCountry($countryGroup, $publisherId)
    {
        foreach ($countryGroup as $group) {
            foreach ($group as $index => $value) {
                $countryCode                                             = $value['country_code'];
                $filename                                                = sprintf('%s-%s.xml', $publisherId, strtolower($countryCode));
                $this->changes[$filename]['included_activities'][$index] = sprintf('%s-%s.xml', $publisherId, strtolower($index));
                $this->changes[$filename]['published_status']            = array_key_exists('published_status', $countryGroup) ? $countryGroup['published_status'] : 0;
            }
        }

        return $this;
    }

    /**
     * Get the changes for segmented publishing type by Recipient Region.
     * @param $regionGroup
     * @param $publisherId
     * @return $this
     */
    protected function getChangesForRegion($regionGroup, $publisherId)
    {
        foreach ($regionGroup as $group) {
            foreach ($group as $index => $value) {
                $regionCode                                              = $value['region_code'];
                $filename                                                = sprintf('%s-%s.xml', $publisherId, $regionCode);
                $this->changes[$filename]['included_activities'][$index] = sprintf('%s-%s.xml', $publisherId, $index);
                $this->changes[$filename]['published_status']            = array_key_exists('published_status', $value) ? $value['published_status'] : 0;
            }
        }

        return $this;
    }

    /**
     * Get the changes for unsegmented publishing type.
     * @param $publisherId
     * @param $currentStatus
     * @return array
     */
    protected function getChangesForUnsegmentedType($publisherId, $currentStatus)
    {
        $activities = [];

        foreach ($currentStatus as $status) {
            if (!empty($status['included_activities'])) {
                foreach ($status['included_activities'] as $key => $includedActivity) {
                    $activities[$key] = $includedActivity;
                }
            }
        }

        if (!empty($activities)) {
            $filename                                        = sprintf('%s-%s.xml', $publisherId, 'activities');
            $this->changes[$filename]['included_activities'] = $activities;
            $this->changes[$filename]['published_status']    = 0;
        }

        return $this->changes;
    }

    /**
     * Get changes for Activities with neither country or region.
     * @param $neither
     * @param $publisherId
     * @return $this
     */
    protected function getChangesForNoRegionOrCountry($neither, $publisherId)
    {
        foreach ($neither as $group) {
            foreach ($group as $index => $value) {
                $regionCode                                              = $value['code'];
                $filename                                                = sprintf('%s-%s.xml', $publisherId, $regionCode);
                $this->changes[$filename]['included_activities'][$index] = sprintf('%s-%s.xml', $publisherId, $index);
                $this->changes[$filename]['published_status']            = array_key_exists('published_status', $value) ? $value['published_status'] : 0;
            }
        }

        return $this;
    }
}
