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
            ->addCollection('category', 'Organization\CategoryCodeForm', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('recipientCountry', 'Organization\RecipientCountryForm', 'recipient_country')
            ->addAddMoreButton('add_recipient_country', 'recipient_country')
            ->addRemoveThisButton('remove_document_link');
    }
}