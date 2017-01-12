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
            ->addSelect('region_code', $this->getCodeList('Region', 'Activity'), trans('elementForm.region_code'), $this->addHelpText('Activity_RecipientRegion-code'), null, true)
            ->addSelect('region_vocabulary', $this->getCodeList('RegionVocabulary', 'Activity'), trans('elementForm.region_vocabulary'), $this->addHelpText('Activity_RecipientRegion-vocabulary'))
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->addPercentage()
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_recipient_region');
    }
}
