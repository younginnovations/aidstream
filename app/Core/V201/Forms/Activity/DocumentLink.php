<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class DocumentLink extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('url', 'text', ['help_block' => $this->addHelpText('Activity_DocumentLink-url')])
            ->addSelect('format', $this->getCodeList('FileFormat', 'Activity'), 'Format', $this->addHelpText('Activity_DocumentLink-format'))
            ->addCollection('title', 'Activity\Title')
            ->addCollection('category', 'Activity\CategoryCode', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language')
            ->addAddMoreButton('add_language', 'language')
            ->addRemoveThisButton('remove_document_link');
    }
}
