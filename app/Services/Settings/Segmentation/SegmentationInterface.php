<?php namespace App\Services\Settings\Segmentation;

use App\Models\Organization\Organization;

interface SegmentationInterface
{

    public function detectSegmentationChangeFor(Organization $organization, $segmentation);

    public function getChanges(Organization $organization, $segmentation, $currentStatus);

    public function extractPublishingType(array $settings);
}