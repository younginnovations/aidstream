<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleParticipatingOrganization
 * @package App\Core\V201\Forms\Activity
 */
class MultipleParticipatingOrganization extends BaseForm
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
                        'class' => 'collection_form participating_organization'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'participating_organization');
    }
}
