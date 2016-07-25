<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class HumanitarianScope
 * @package App\Core\V202\Forms\Activity
 */
class HumanitarianScope extends BaseForm
{
    /**
     * build humanitarian scope form
     */
    public function buildForm()
    {
        $this
            ->addSelect('type', $this->getCodeList('HumanitarianScopeType', 'Activity'), null, null, null, true, ['attr' => ['class' => 'humanitarian-type form-control']])
            ->addSelect(
                'vocabulary',
                $this->getCodeList('HumanitarianScopeVocabulary', 'Activity'),
                null,
                null,
                null,
                true,
                ['attr' => ['class' => 'humanitarian-vocabulary form-control', 'disabled' => 'disabled']]
            )
            ->add('vocabulary_uri', 'text')
            ->add('code', 'text', ['required' => true])
            ->addNarrative('humanitarian_narrative')
            ->addAddMoreButton('add', 'humanitarian_narrative')
            ->addRemoveThisButton('remove');
    }
}
