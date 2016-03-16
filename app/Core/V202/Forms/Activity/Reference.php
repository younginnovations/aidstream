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
            ->addSelect('vocabulary', $this->getCodeList('IndicatorVocabulary', 'Activity'), null, null, null, true)
            ->add('code', 'text', ['required' => true])
            ->add('indicator_uri', 'text', ['label' => 'Indicator URI'])
            ->addRemoveThisButton('remove_reference');
    }
}
