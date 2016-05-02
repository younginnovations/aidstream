<?php namespace App\Services\Settings\Segmentation\Rule;

use App\Services\Settings\Segmentation\Rule\Traits\OutlinesChangesByGeopoliticalInformation;

/**
 * Class SegmentationProtocol
 * @package App\Services\Settings\Segmentation\Rule
 */
class SegmentationProtocol
{
    use OutlinesChangesByGeopoliticalInformation;
    /**
     * Segmented publishing type.
     */
    const SEGMENTED = 'segmented';

    /**
     * Unsegmented publishing type.
     */
    const UNSEGMENTED = 'unsegmented';

    /**
     * @var array
     */
    protected $changes = [];

    /**
     * Get the changes incurred while changing the pubishing type.
     * @param       $publisherId
     * @param       $segmentation
     * @param       $currentStatus
     * @param array $countryGroup
     * @param array $regionGroup
     * @param array $neither
     * @return array
     */
    public function getChanges($publisherId, $segmentation, $currentStatus, $countryGroup = [], $regionGroup = [], $neither = [])
    {
        if ($segmentation === self::SEGMENTED) {
            $this->getChangesForCountry($countryGroup, $publisherId)->getChangesForRegion($regionGroup, $publisherId)->getChangesForNoRegionOrCountry($neither, $publisherId);
        } else {
            $this->getChangesForUnsegmentedType($publisherId, $currentStatus);
        }

        return ['previous' => $currentStatus, 'changes' => $this->changes];
    }
}
