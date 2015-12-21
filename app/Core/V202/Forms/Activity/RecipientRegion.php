<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RecipientRegion
 * @package App\Core\V202\Forms\Activity
 */
class RecipientRegion extends BaseForm
{
    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $this
            ->addSelect('region_code', $this->getCodeList('Region', 'Activity'))
            ->addSelect('region_vocabulary', $this->getCodeList('RegionVocabulary', 'Activity'))
            ->add('vocabulary_uri', 'text', ['label' => 'Vocabulary URI'])
            ->addPercentage()
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_recipient_region');
    }
}
