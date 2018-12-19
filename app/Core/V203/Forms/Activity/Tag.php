<?php 
namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V203\Traits\Forms\Tag as TagCodeList;

/**
 * Class Tag
 * @package App\Core\V203\Forms\Activity
 */
class Tag extends BaseForm
{
    use TagCodeList;

    public function buildForm()
    {
        $this
            ->add(
                'tag_vocabulary',
                'select',
                [
                    'choices'       => $this->getTagVocabularyCodeList(),
                    'empty_value'   => trans('elementForm.select_text'),
                    'default_value' => '1',
                    'attr'          => ['class' => 'form-control tag_vocabulary'],
                    'label'         => trans('elementForm.tag_vocabulary'),
                    'required'      => true
                ]
            )
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->add(
                'tag_code',
                'text',
                [
                    'label'       => trans('elementForm.tag_code'),
                    'wrapper'     => ['class' => 'form-group sector_types sector_select'],
                    'required'    => true
                ]
            )
            ->add(
                'tag_text',
                'text',
                [
                    'label'    => trans('elementForm.tag'),
                    'wrapper'  => ['class' => 'form-group hidden sector_types tag_text'],
                    'required' => true
                ]
            )
            ->addNarrative('tag_narrative',null,['narrative_required' => false])
            ->addAddMoreButton('add', 'tag_narrative')
            ->addRemoveThisButton('remove');
    }
}
