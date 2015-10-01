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
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                        'label' => 'Narrative',
                    ],
                    'wrapper' => [
                        'class' => 'collection_form narrative'
                    ]
                ]
            )
            ->add(
                'Add More1',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'narrative'
                    ]
                ]
            )
            ->add(
                'category',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\CategoryCodeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form category'
                    ]
                ]
            )
            ->add(
                'Add More2',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'category'
                    ]
                ]
            )
            ->add(
                'language',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\LanguageCodeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form language'
                    ]
                ]
            )
            ->add(
                'Add More3',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'language'
                    ]
                ]
            )
            ->add(
                'recipientCountry',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientCountryForm',
                        'label' => false
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_country'
                    ]
                ]
            )
            ->add(
                'Add More4',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'recipient_country'
                    ]
                ]
            )
            ->add(
                'Remove this',
                'button',
                [
                    'attr' => [
                        'class' => 'remove_from_collection',
                    ]
                ]
            );
    }
}