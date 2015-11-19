<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class DocumentLink extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('url', 'text')
            ->add(
                'format',
                'select',
                [
                    'choices' => $this->getCodeList('FileFormat', 'Activity')
                ]
            )
            ->addCollection('title', 'Activity\Title')
            ->addCollection('category', 'Activity\CategoryCode', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language')
            ->addAddMoreButton('add_language', 'language')
            ->addRemoveThisButton('remove_document_link');
    }
}
