<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Reference
 * @package App\Core\V202\Forms\Activity
 */
class ResultReference extends BaseForm
{
    /**
     * builds the result reference form
     */
    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('ResultVocabulary', 'Activity'), trans('elementForm.vocabulary'))
            ->add('code', 'text', ['label' => trans('elementForm.code')])
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->addRemoveThisButton('remove_reference');
    }
}
