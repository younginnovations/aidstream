<?php namespace App\Core\V202\Forms\Activity\Transactions;

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
            ->addSelect('region_code', $this->getRegionCodeList())
            ->addSelect('vocabulary', $this->getRegionVocabularyCodeList())
            ->add('vocabulary_uri', 'text', ['label' => 'Vocabulary URI'])
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
