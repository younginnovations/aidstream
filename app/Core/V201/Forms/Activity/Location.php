<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Location
 * @package App\Core\V201\Forms\Activity
 */
class Location extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location form
     */
    public function buildForm()
    {
        $this
            ->add('reference', 'text', ['help_block' => $this->addHelpText('Activity_Location-ref')])
            ->addCollection('location_reach', 'Activity\LocationReach')
            ->addCollection('location_id', 'Activity\LocationId', 'location_id')
            ->addAddMoreButton('add', 'location_id')
            ->addCollection('name', 'Activity\Name')
            ->addCollection('location_description', 'Activity\LocationDescription')
            ->addCollection('activity_description', 'Activity\ActivityDescription')
            ->addCollection('administrative', 'Activity\Administrative', 'administrative')
            ->addAddMoreButton('add_administrative', 'administrative')
            ->addCollection('point', 'Activity\Point')
            ->addCollection('exactness', 'Activity\Exactness')
            ->addCollection('location_class', 'Activity\LocationClass')
            ->addCollection('feature_designation', 'Activity\FeatureDesignation')
            ->addRemoveThisButton('remove');
    }
}
