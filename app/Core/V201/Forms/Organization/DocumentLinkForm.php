<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class DocumentLinkForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('url', 'text')
            ->add(
                'format',
                'select',
                [
                    'choices' => [
                        'msword' => 'application/msword - Microsoft Word',
                        'msexel' => 'application/msexel - Microsoft Exel'
                    ],
                    'label'   => 'Format'
                ]
            )
            ->add(
                'title',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                        'label' => 'Narrative',
                    ],
                ]
            )
            ->add(
                'category',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Organization\CategoryCodeForm',
                        'label' => false,
                    ],
                ]
            )
            ->add(
                'language',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Organization\LanguageCodeForm',
                        'label' => false,
                    ],
                ]
            )
            ->add(
                'recipientCountry',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientCountryForm',
                        'label' => false
                    ]
                ]
            );
    }
}