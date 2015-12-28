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
            ->addSelect('type', $this->getCodeList('HumanitarianScopeType', 'Activity'))
            ->addSelect('vocabulary', $this->getCodeList('HumanitarianScopeVocabulary', 'Activity'))
            ->add('vocabulary_uri', 'text')
            ->add('code', 'text')
            ->addNarrative('humanitarian_narrative')
            ->addAddMoreButton('add', 'humanitarian_narrative')
            ->addRemoveThisButton('remove');
    }
}
