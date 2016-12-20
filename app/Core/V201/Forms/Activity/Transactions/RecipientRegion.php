<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\RecipientRegion as RecipientRegionCodeList;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Forms\Activity
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
            ->addSelect('region_code', $this->getRegionCodeList(), trans('elementForm.region_code'), $this->addHelpText('Activity_Transaction_RecipientRegion-code'))
            ->addSelect('vocabulary', $this->getRegionVocabularyCodeList(), trans('elementForm.vocabulary'), $this->addHelpText('Activity_Transaction_RecipientRegion-vocabulary'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
