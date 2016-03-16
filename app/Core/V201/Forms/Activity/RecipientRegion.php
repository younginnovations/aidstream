<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Forms\Activity
 */
class RecipientRegion extends BaseForm
{
    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $this
            ->addSelect('region_code', $this->getCodeList('Region', 'Activity'), 'Region Code', $this->addHelpText('Activity_RecipientRegion-code'), null, true)
            ->addSelect('region_vocabulary', $this->getCodeList('RegionVocabulary', 'Activity'), 'Region Vocabulary', $this->addHelpText('Activity_RecipientRegion-vocabulary'))
            ->addPercentage($this->addHelpText('Activity_RecipientRegion-percentage'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_recipient_region');
    }
}
