<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Reference
 * @package App\Core\V202\Forms\Activity
 */
class Reference extends BaseForm
{
    /**
     * builds the result reference form
     */
    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('IndicatorVocabulary', 'Activity'), trans('elementForm.vocabulary'))
            ->add('code', 'text', ['label' => trans('elementForm.code')])
            ->add('indicator_uri', 'text', ['label' => trans('elementForm.indicator_uri')])
            ->addRemoveThisButton('remove_reference');
    }
}
