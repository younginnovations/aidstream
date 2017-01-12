<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class ActivityDate extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add(
                'date',
                'date',
                ['label' => trans('elementForm.date'), 'help_block' => $this->addHelpText('Activity_ActivityDate-iso_date'), 'required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']]
            )
            ->addSelect('type', $this->getCodeList('ActivityDateType', 'Activity'), trans('elementForm.activity_date_type'), $this->addHelpText('Activity_ActivityDate-type'), null, true)
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_activity_date');
    }
}
