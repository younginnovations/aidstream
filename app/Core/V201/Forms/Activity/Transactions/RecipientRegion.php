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
            ->add(
                'region_code',
                'select',
                [
                    'choices' => $this->getRegionCodeList(),
                ]
            )
            ->add(
                'vocabulary',
                'select',
                [
                    'choices' => $this->getRegionVocabularyCodeList(),
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
