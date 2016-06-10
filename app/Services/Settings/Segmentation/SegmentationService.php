<?php namespace App\Services\Settings\Segmentation;

use App\Models\Organization\Organization;
use App\Services\Settings\Segmentation\Rule\SegmentationProtocol;

/**
 * Class SegmentationService
 * @package App\Services\Settings\Segmentation
 */
class SegmentationService implements SegmentationInterface
{
    /**
     * @var
     */
    protected $organization;

    /**
     * @var SegmentationProtocol
     */
    protected $segmentationProtocol;

    /**
     * SegmentationService constructor.
     * @param SegmentationProtocol $segmentationProtocol
     */
    public function __construct(SegmentationProtocol $segmentationProtocol)
    {
        $this->segmentationProtocol = $segmentationProtocol;
    }

    /**
     * Detect if a change in segmentation has been made.
     * @param Organization $organization
     * @param              $segmentation
     * @return bool
     */
    public function detectSegmentationChangeFor(Organization $organization, $segmentation)
    {
        return ($organization->settings->publishing_type !== $segmentation);
    }

    /**
     * Get changes for the segmentation change.
     * @param Organization $organization
     * @param              $segmentation
     * @param              $currentStatus
     * @return array
     */
    public function getChanges(Organization $organization, $segmentation, $currentStatus)
    {
        $publisherId  = $this->extractPublisherId($organization->settings->toArray());
        $activityData = [];

        foreach ($organization->activities as $activity) {
            $activityData[$activity->id] = $this->getActivityMetaData($activity);
        }

        $countryGroup = $this->groupBy('country_code', $activityData);
        $regionGroup  = $this->groupBy('region_code', $activityData);
        $neither      = $this->groupBy('code', $activityData);

        $changes = $this->segmentationProtocol->getChanges($publisherId, $segmentation, $currentStatus, $countryGroup, $regionGroup, $neither);

        $changes['publisher_id'] = $publisherId;
        $changes['segmentation'] = $segmentation;

        return $changes;
    }

    /**
     * Extract Publishing type from the Organization's Settings.
     * @param array $settings
     * @return string
     */
    public function extractPublishingType(array $settings)
    {
        if (is_array($settings['publishing_type'])) {
            return (array_key_exists(0, $settings['publishing_type'])) ? $settings['publishing_type'][0]['publishing'] : '';
        }

        return $settings['publishing_type'];
    }

    /**
     * Get Activity meta data required for the change in segmentation.
     * @param $activity
     * @return null
     */
    protected function getActivityMetaData($activity)
    {
        if ($activity->activity_workflow == 3) {
            if ($activity->recipient_country) {
                return $this->recipientCountry($activity->recipient_country, $activity->published_to_registry);
            }

            if ($activity->recipient_region) {
                return $this->recipientRegion($activity->recipient_region, $activity->published_to_registry);
            }

            return ['code' => 998];
        }

        return [];
    }

    /**
     * Get Activity meta data for Recipient Country.
     * @param $countries
     * @param $publishedStatus
     * @return null
     */
    protected function recipientCountry($countries, $publishedStatus)
    {
        $max             = 0;
        $requiredCountry = null;

        foreach ($countries as $country) {
            if ($country['percentage'] > $max) {
                $max             = $country['percentage'];
                $requiredCountry = $country;
            } elseif ($country['percentage'] == "") {
                $requiredCountry = $country;
            }
        }

        $requiredCountry['published_status'] = $publishedStatus;

        return $requiredCountry;
    }

    /**
     * Get Activity meta data for Recipient Region.
     * @param $regions
     * @param $publishedStatus
     * @return null
     */
    protected function recipientRegion($regions, $publishedStatus)
    {
        $max            = 0;
        $requiredRegion = null;

        foreach ($regions as $region) {
            if ($region['percentage']) {
                if ($region['percentage'] > $max) {
                    $max            = $region['percentage'];
                    $requiredRegion = $region;
                }
            } else {
                $requiredRegion = $region;
            }
        }

        $requiredRegion['published_status'] = $publishedStatus;

        return $requiredRegion;
    }

    /**
     * Group the Activity data by their country/region codes.
     * @param $code
     * @param $activityData
     * @return array
     */
    protected function groupBy($code, $activityData)
    {
        $grouped = [];

        foreach ($activityData as $id => $activity) {
            if ($activity && array_key_exists($code, $activity)) {
                $grouped[$activity[$code]][$id] = $activity;
            }
        }

        return $grouped;
    }

    /**
     * Extract the publisherId from the Organization's Settings.
     * @param array $settings
     * @return null
     */
    protected function extractPublisherId(array $settings)
    {
        if (is_array($settings['registry_info'])) {
            if (array_key_exists(0, $settings['registry_info'])) {
                return $settings['registry_info'][0]['publisher_id'];
            }
        }

        return null;
    }
}
