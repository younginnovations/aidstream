<?php namespace App\Core\V203\Repositories\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DocumentLink
 * @package App\Core\V201\Forms\Activity
 */
class DocumentLink extends BaseForm
{
    /**
     * build document link form
     */
    public function buildForm()
    {
        $this
            ->add(
                'url',
                'text',
                ['label' => trans('elementForm.url'), 'attr' => ['class' => 'form-control document_link'], 'help_block' => $this->addHelpText('Activity_DocumentLink-url'), 'required' => true]
            )
            ->addSelect('format', $this->getCodeList('FileFormat', 'Activity'), trans('elementForm.format'), $this->addHelpText('Activity_DocumentLink-format'), null, true)
            ->add(
                'upload_text',
                'static',
                [
                    'tag'           => 'em',
                    'label'         => false,
                    'default_value' => trans('elementForm.url_text')
                ]
            )
            ->addCollection('title', 'Activity\Title', '', [], trans('elementForm.title'))
            ->addCollection('description', 'Activity\Title', '', [], trans('elementForm.description'))
            ->addCollection('category', 'Activity\CategoryCode', 'category', [], trans('elementForm.category'))
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language', [], trans('elementForm.language'))
            ->addAddMoreButton('add_language', 'language');
    }
}
