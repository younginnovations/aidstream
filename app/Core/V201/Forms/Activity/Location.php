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
            ->add('reference', 'text')
            ->add(
                'location_reach',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\LocationReach',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'location_id',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\LocationId',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form location_id'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'location_id')
            ->add(
                'name',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Name',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form'
                    ]
                ]
            )
            ->add(
                'location_description',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\LocationDescription',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form'
                    ]
                ]
            )
            ->add(
                'activity_description',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\ActivityDescription',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form'
                    ]
                ]
            )
            ->add(
                'administrative',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Administrative',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form administrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_administrative', 'administrative')
            ->add(
                'point',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Point',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'exactness',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Exactness',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'location_class',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\LocationClass',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'feature_designation',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\FeatureDesignation',
                        'label' => false,
                    ]
                ]
            )
            ->addRemoveThisButton('remove');
    }
}
