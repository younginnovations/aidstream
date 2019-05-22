<?php namespace App\Core\V203\Forms\Activity;

use App\Core\V201\Forms\Activity\DocumentLink as V201DocumentLink;

class DocumentLink extends V201DocumentLink
{
    public function buildForm()
    {
        $this
            ->add('url', 'text', ['label' => trans('elementForm.url'), 'attr' => ['class' => 'document_link form-control'], 'help_block' => $this->addHelpText('Activity_DocumentLink-url'), 'required' => true])
            ->addSelect('format', $this->getCodeList('FileFormat', 'Activity'), trans('elementForm.format'), $this->addHelpText('Activity_DocumentLink-format'), null, true, ['attr' => ['class' => 'document_link_format form-control']])
            ->add(
                'upload_text',
                'static',
                [
                    'tag'           => 'em',
                    'label'         => false,
                    'default_value' => trans('elementForm.url_text')
                ]
            )
            ->addCollection('title', 'Activity\Title', '', [ 'narrative_true' => true ], trans('elementForm.title'))
            ->addCollection('description', 'Activity\Title', '', ['class' => 'description_narrative'], trans('elementForm.description'))
            ->addCollection('category', 'Activity\CategoryCode', 'category', [], trans('elementForm.category'))
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language', [], trans('elementForm.language'))
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('document_date', 'Activity\Date', '', [], trans('elementForm.document_date'))
            ->addRemoveThisButton('remove_document_link');
    }
}
