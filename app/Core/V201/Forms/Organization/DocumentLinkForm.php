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
                    'choices'     => $this->getCodeList('FileFormat', 'Organization'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Format'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addCollection('category', 'Organization\CategoryCodeForm', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Organization\LanguageCodeForm', 'language')
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('recipient_country', 'Organization\RecipientCountryForm', 'recipient_country')
            ->addAddMoreButton('add_recipient_country', 'recipient_country')
            ->addRemoveThisButton('remove_document_link');
    }
}
