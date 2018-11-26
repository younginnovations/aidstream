<?php namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\RecipientRegion as RecipientRegionCodeList;

/**
 * Class RecipientRegion
 * @package App\Core\V202\Forms\Activity
 */
class RecipientRegion extends BaseForm
{
    use RecipientRegionCodeList;

    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $this
            ->addSelect('region_code', $this->getRegionCodeList(), trans('elementForm.region_code'))
            ->addSelect('vocabulary', $this->getRegionVocabularyCodeList(), trans('elementForm.vocabulary'))
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
