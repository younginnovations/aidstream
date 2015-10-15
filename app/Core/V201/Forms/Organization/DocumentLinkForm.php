<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class DocumentLinkForm extends BaseForm
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
                    'label' => 'Format'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->add(
                'category',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\CategoryCodeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form category'
                    ]
                ]
            )
            ->addAddMoreButton('add_category', 'category')
            ->add(
                'language',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\LanguageCodeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form language'
                    ]
                ]
            )
            ->addAddMoreButton('add_language', 'language')
            ->add(
                'recipientCountry',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientCountryForm',
                        'label' => false
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_country'
                    ]
                ]
            )
            ->addAddMoreButton('add_recipient_country', 'recipient_country')
            ->addRemoveThisButton('remove_document_link');
    }
}
