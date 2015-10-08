<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleParticipatingOrganization
 * @package App\Core\V201\Forms\Activity
 */
class MultipleParticipatingOrganization extends Form
{
    /**
     * builds activity Participating Organization
     */
    public function buildForm()
    {
        $this
            ->add(
                'participating_organization',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\ParticipatingOrganization',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form participatingOrganization'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'participatingOrganization'
                    ]
                ]
            );
    }
}
