<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class RecipientRegion
 * @package App\Core\V202\Forms\Organization
 */
class RecipientRegion extends BaseForm
{
    /**
     * build recipient region form
     */
    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('RegionVocabulary', 'Activity'), trans('elementForm.vocabulary'))
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->addSelect('code', $this->getCodeList('Region', 'Activity'), trans('elementForm.code'), null, null, true)
            ->addNarrative('recipient_region_narrative')
            ->addAddMoreButton('add_recipient_region_narrative', 'recipient_region_narrative')
            ->addRemoveThisButton('remove_recipient_region');
    }
}
